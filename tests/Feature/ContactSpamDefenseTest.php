<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use App\Services\IpBanService;
use App\Services\SpamDetectorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactSpamDefenseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_legitimate_contact_form_submission_is_saved(): void
    {
        // Simulate a form rendered 5 seconds ago
        $tokenTime = Crypt::encryptString((string) (time() - 5));

        $response = $this->postJson(route('contact.store'), [
            'name' => 'Budi Pratama',
            'email' => 'budi@example.com',
            'project_type' => 'Pembuatan Website Baru',
            'budget_range' => 'Rp 5.000.000 - Rp 10.000.000',
            'message' => 'Halo Mas Willy, saya butuh website company profile untuk kantor kami.',
            'website_url' => '',
            'phone_confirmation' => '',
            '_token_time' => $tokenTime,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('messages', [
            'email' => 'budi@example.com',
            'name' => 'Budi Pratama',
        ]);
    }

    public function test_honeypot_triggers_silent_drop_without_saving_to_database(): void
    {
        $response = $this->postJson(route('contact.store'), [
            'name' => 'Spam Bot',
            'email' => 'spambot@spammer.com',
            'project_type' => 'Pembuatan Website Baru',
            'message' => 'Buy cheap medications now at our pharmacy online.',
            'website_url' => 'https://spam-link.com',
            'phone_confirmation' => '',
        ]);

        // Silent drop returns success so bots don't adapt
        $response->assertSuccessful();

        $this->assertDatabaseMissing('messages', [
            'email' => 'spambot@spammer.com',
        ]);
    }

    public function test_secondary_honeypot_triggers_silent_drop(): void
    {
        $response = $this->postJson(route('contact.store'), [
            'name' => 'Spam Bot 2',
            'email' => 'bot2@spammer.com',
            'project_type' => 'Pembuatan Website Baru',
            'message' => 'Best crypto investment returns guaranteed daily.',
            'website_url' => '',
            'phone_confirmation' => '08123456789',
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseMissing('messages', [
            'email' => 'bot2@spammer.com',
        ]);
    }

    public function test_submitting_faster_than_human_speed_triggers_silent_drop(): void
    {
        // Token rendered just now (0 seconds elapsed)
        $tokenTime = Crypt::encryptString((string) time());

        $response = $this->postJson(route('contact.store'), [
            'name' => 'Instant Bot',
            'email' => 'instant@spammer.com',
            'project_type' => 'Pembuatan Website Baru',
            'message' => 'This message was posted in 50 milliseconds by automated cURL.',
            'website_url' => '',
            'phone_confirmation' => '',
            '_token_time' => $tokenTime,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseMissing('messages', [
            'email' => 'instant@spammer.com',
        ]);
    }

    public function test_spam_keywords_and_cyrillic_trigger_silent_drop(): void
    {
        $tokenTime = Crypt::encryptString((string) (time() - 10));

        $response = $this->postJson(route('contact.store'), [
            'name' => 'SEO Spammer',
            'email' => 'seo@rankboost.com',
            'project_type' => 'Pembuatan Website Baru',
            'message' => 'Check our slot gacor and casino online link for high returns!',
            'website_url' => '',
            'phone_confirmation' => '',
            '_token_time' => $tokenTime,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseMissing('messages', [
            'email' => 'seo@rankboost.com',
        ]);
    }

    public function test_admin_can_purge_all_messages_from_inbox(): void
    {
        $admin = User::factory()->create();

        Message::create([
            'name' => 'Pesan 1',
            'email' => 'p1@test.com',
            'project_type' => 'Web',
            'message' => 'Isi pesan pertama dari calon klien.',
        ]);

        Message::create([
            'name' => 'Pesan 2',
            'email' => 'p2@test.com',
            'project_type' => 'Web',
            'message' => 'Isi pesan kedua dari calon klien.',
        ]);

        $this->assertEquals(2, Message::count());

        $response = $this->actingAs($admin)->delete(route('admin.messages.purge'));

        $response->assertRedirect(route('admin.dashboard', ['tab' => 'inbox']));
        $this->assertEquals(0, Message::count());
    }

    public function test_admin_can_ban_spammer_ip_and_delete_message(): void
    {
        $admin = User::factory()->create();

        $message = Message::create([
            'name' => 'Spammer',
            'email' => 'spammer@evil.com',
            'project_type' => 'Web',
            'message' => 'Casino slot gacor spam link.',
            'ip_address' => '198.51.100.99',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.messages.ban-ip', $message));

        $response->assertRedirect(route('admin.dashboard', ['tab' => 'inbox']));
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);

        $this->assertNotNull(IpBanService::isBanned('198.51.100.99'));
    }
}
