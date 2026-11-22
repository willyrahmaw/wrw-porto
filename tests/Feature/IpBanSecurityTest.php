<?php

namespace Tests\Feature;

use App\Models\IpBan;
use App\Models\User;
use App\Services\IpBanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class IpBanSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_ip_is_banned_after_two_failed_password_attempts(): void
    {
        $this->seed();
        $targetIp = '103.24.56.78';

        // Attempt 1: Wrong password -> not yet banned
        $res1 = $this->withServerVariables(['REMOTE_ADDR' => $targetIp])->post('/admin/login', [
            'email' => 'admin@wrw.studio',
            'password' => 'wrongpass1',
        ]);
        $res1->assertSessionHasErrors('email');
        $this->assertNull(IpBanService::isBanned($targetIp));

        // Attempt 2: Wrong password again -> trigger Strike 1 ban (1 hour)
        $res2 = $this->withServerVariables(['REMOTE_ADDR' => $targetIp])->post('/admin/login', [
            'email' => 'admin@wrw.studio',
            'password' => 'wrongpass2',
        ]);
        $res2->assertStatus(403);
        $res2->assertSee('IP RESTRICTED');
        $res2->assertSee('Tier 1 (1 Jam)');

        // Verify IP is recorded in database
        $ban = IpBan::where('ip_address', $targetIp)->first();
        $this->assertNotNull($ban);
        $this->assertEquals(1, $ban->strike_count);
        $this->assertFalse($ban->is_permanent);
        $this->assertNotNull($ban->banned_until);

        // Subsequent visits from this IP are blocked by IpBanMiddleware
        $visit = $this->withServerVariables(['REMOTE_ADDR' => $targetIp])->get('/');
        $visit->assertStatus(403);
        $visit->assertSee('IP RESTRICTED');
        $visit->assertSee($targetIp);
    }

    public function test_scripting_injection_triggers_ip_ban(): void
    {
        $this->seed();
        $targetIp = '198.51.100.44';

        // Attack probe with SQL injection
        $attack = $this->withServerVariables(['REMOTE_ADDR' => $targetIp])->get('/?search=test%20UNION%20SELECT%201,2');
        $attack->assertStatus(403);
        $attack->assertSee('IP RESTRICTED');

        // Verify ban
        $ban = IpBan::where('ip_address', $targetIp)->first();
        $this->assertNotNull($ban);
        $this->assertStringContainsString('Scripting Injection Attack', $ban->reason);
    }

    public function test_tiered_strike_progression_up_to_permanent(): void
    {
        $testIp = '203.0.113.99';

        // Strike 1 -> 1 Hour
        $b1 = IpBanService::ban($testIp, 'First infraction');
        $this->assertEquals(1, $b1->strike_count);
        $this->assertFalse($b1->is_permanent);
        $this->assertTrue($b1->banned_until->gt(now()->addMinutes(50)));

        // Strike 2 -> 3 Hours
        $b2 = IpBanService::ban($testIp, 'Second infraction');
        $this->assertEquals(2, $b2->strike_count);
        $this->assertFalse($b2->is_permanent);
        $this->assertTrue($b2->banned_until->gt(now()->addHours(2)));

        // Strike 3 -> Permanent
        $b3 = IpBanService::ban($testIp, 'Third infraction');
        $this->assertEquals(3, $b3->strike_count);
        $this->assertTrue($b3->is_permanent);
        $this->assertNull($b3->banned_until);
    }

    public function test_admin_can_view_and_delete_banned_ip(): void
    {
        $this->seed();
        $user = User::first();
        $testIp = '198.51.100.88';

        $ban = IpBanService::ban($testIp, 'Uji coba pemblokiran');

        // Admin can see the IP in dashboard
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee($testIp);

        // Admin deletes the ban (unban)
        $deleteResponse = $this->actingAs($user)->delete("/admin/banned-ips/{$ban->id}");
        $deleteResponse->assertRedirect();

        $this->assertDatabaseMissing('ip_bans', ['ip_address' => $testIp]);
        $this->assertNull(IpBanService::isBanned($testIp));

        // The IP can now access the website normally
        $visit = $this->withServerVariables(['REMOTE_ADDR' => $testIp])->get('/');
        $visit->assertStatus(200);
    }

    public function test_admin_can_manually_ban_ip(): void
    {
        $this->seed();
        $user = User::first();
        $targetIp = '103.111.222.33';

        $manualResponse = $this->actingAs($user)->post('/admin/banned-ips', [
            'ip_address' => $targetIp,
            'duration' => 'permanent',
            'reason' => 'Known malicious bot subnet',
        ]);

        $manualResponse->assertRedirect();

        $ban = IpBan::where('ip_address', $targetIp)->first();
        $this->assertNotNull($ban);
        $this->assertTrue($ban->is_permanent);
        $this->assertEquals(3, $ban->strike_count);

        // Log out admin so subsequent visit is from a guest
        $this->app['auth']->logout();

        // The target IP is blocked
        $visit = $this->withServerVariables(['REMOTE_ADDR' => $targetIp])->get('/');
        $visit->assertStatus(403);
    }

    public function test_authenticated_admin_is_immune_from_ip_ban(): void
    {
        $this->seed();
        $user = User::first();
        $adminIp = '103.55.66.77';

        // Ban the admin's IP
        IpBanService::ban($adminIp, 'Accidental ban during penetration test');

        // Without auth -> 403 Forbidden
        $guestVisit = $this->withServerVariables(['REMOTE_ADDR' => $adminIp])->get('/');
        $guestVisit->assertStatus(403);

        // When authenticated -> 200 OK (Immunity active)
        $authVisit = $this->actingAs($user)->withServerVariables(['REMOTE_ADDR' => $adminIp])->get('/admin');
        $authVisit->assertStatus(200);
    }

    public function test_banned_ip_can_emergency_unban_using_admin_password(): void
    {
        $this->seed();
        $targetIp = '103.99.88.77';

        // Ban IP
        IpBanService::ban($targetIp, 'Accidental test ban');
        $this->assertNotNull(IpBanService::isBanned($targetIp));

        // Submit emergency unban with correct admin password
        $response = $this->withServerVariables(['REMOTE_ADDR' => $targetIp])
            ->post('/admin/emergency-unban', [
                'password' => 'password123',
            ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        // Verify IP is unbanned
        $this->assertNull(IpBanService::isBanned($targetIp));

        // Subsequent visits now 200 OK
        $visit = $this->withServerVariables(['REMOTE_ADDR' => $targetIp])->get('/');
        $visit->assertStatus(200);
    }
}
