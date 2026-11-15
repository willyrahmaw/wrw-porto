<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_displays_open_slot_by_default(): void
    {
        $this->seed();

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Slot Terbuka • Sistem 1 Klien, 1 Fokus Penuh');
    }

    public function test_admin_can_toggle_availability_instantly(): void
    {
        $this->seed();
        $user = User::first();
        $profile = Profile::first();

        $this->assertTrue($profile->is_available);

        // Toggle to false (Closed)
        $toggleResponse = $this->actingAs($user)->patch('/admin/profile/toggle-availability');
        $toggleResponse->assertRedirect();

        $this->assertFalse($profile->fresh()->is_available);

        // Landing page now reflects unavailable status
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($profile->fresh()->unavailable_status);

        // Toggle back to true (Open)
        $toggleResponse2 = $this->actingAs($user)->patch('/admin/profile/toggle-availability');
        $toggleResponse2->assertRedirect();

        $this->assertTrue($profile->fresh()->is_available);
    }

    public function test_admin_can_update_availability_texts_and_toggle_via_profile_form(): void
    {
        $this->seed();
        $user = User::first();
        $profile = Profile::first();

        $updateResponse = $this->actingAs($user)->put('/admin/profile', [
            'name' => $profile->name,
            'initials' => $profile->initials,
            'profession' => $profile->profession,
            'tagline' => $profile->tagline,
            'bio' => $profile->bio,
            'location' => $profile->location,
            'availability_status' => 'Menerima 2 Proyek Bulan Ini',
            'unavailable_status' => 'Jadwal Penuh Hingga Akhir Bulan',
            'is_available' => false,
            'email' => $profile->email,
            'whatsapp' => $profile->whatsapp,
            'trusted_clients_count' => 20,
            'trusted_industries_count' => 7,
        ]);

        $updateResponse->assertRedirect();

        $updated = Profile::first();
        $this->assertFalse($updated->is_available);
        $this->assertEquals('Menerima 2 Proyek Bulan Ini', $updated->availability_status);
        $this->assertEquals('Jadwal Penuh Hingga Akhir Bulan', $updated->unavailable_status);

        // Homepage displays the custom unavailable text
        $home = $this->get('/');
        $home->assertSee('Jadwal Penuh Hingga Akhir Bulan');
    }
}

