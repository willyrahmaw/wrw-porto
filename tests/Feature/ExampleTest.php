<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify landing page, cached second hit, project detail, and admin login work.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed();

        // First hit (populates cache)
        $response1 = $this->get('/');
        $response1->assertStatus(200);
        $response1->assertSee('Sertifikasi');
        $response1->assertSee('01 // VERIFIED CREDENTIALS');

        // Second hit (reads from cache & unserializes Models)
        $response2 = $this->get('/');
        $response2->assertStatus(200);

        // Projects archive & Admin login page
        $this->get('/projects')->assertStatus(200);
        $this->get('/admin/login')->assertStatus(200);

        // Authenticated admin can access dedicated project create & edit pages
        $user = \App\Models\User::first();
        $project = \App\Models\Project::first();
        $this->actingAs($user)->get('/admin/projects/create')->assertStatus(200);
        $this->actingAs($user)->get("/admin/projects/{$project->id}/edit")->assertStatus(200);

        // Contact submission without budget range
        $contactResponse = $this->postJson('/contact', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'project_type' => 'Pembuatan Website Baru',
            'message' => 'Halo, saya ingin membuat website toko online untuk bisnis retail.',
        ]);
        $contactResponse->assertStatus(201);
        $this->assertDatabaseHas('messages', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
        ]);

        // Test updating stats from admin
        $profile = \App\Models\Profile::first();
        $this->actingAs($user)->put('/admin/profile', [
            'name' => $profile->name,
            'initials' => $profile->initials,
            'profession' => $profile->profession,
            'tagline' => $profile->tagline,
            'bio' => $profile->bio,
            'location' => $profile->location,
            'availability_status' => $profile->availability_status,
            'email' => $profile->email,
            'whatsapp' => $profile->whatsapp,
            'trusted_clients_count' => 20,
            'trusted_industries_count' => 8,
            'stats' => [
                ['value' => 5, 'suffix' => '+', 'label' => 'Tahun Pengalaman'],
                ['value' => 30, 'suffix' => '+', 'label' => 'Proyek Selesai'],
                ['value' => 99, 'suffix' => '%', 'label' => 'Skor Kecepatan Web'],
                ['value' => 12, 'suffix' => ' Jam', 'label' => 'Respon Support'],
            ],
            'certificates' => [
                [
                    'title' => 'Laravel Master Certification',
                    'issuer' => 'Laravel Official',
                    'year' => '2025',
                    'credential_id' => 'LMC-2025-01',
                    'file_path' => null,
                    'url' => 'https://laravel.com',
                    'badge' => 'Mastery',
                ]
            ],
            'certificate_files' => [
                0 => \Illuminate\Http\UploadedFile::fake()->create('sertifikat.pdf', 200, 'application/pdf'),
            ],
        ])->assertRedirect();

        $this->assertEquals(5, \App\Models\Profile::first()->stats[0]['value']);
        $this->assertEquals('Laravel Master Certification', \App\Models\Profile::first()->certificates[0]['title']);
        $this->assertNotNull(\App\Models\Profile::first()->certificates[0]['file_path']);

        // Test updating SEO & Favicon settings from admin
        $fakeFavicon = \Illuminate\Http\UploadedFile::fake()->create('custom-icon.png', 20, 'image/png');
        $this->actingAs($user)->put('/admin/seo', [
            'meta_title' => 'Willy Rahmat Wijaya — Top Full-Stack Engineer',
            'meta_description' => 'Membangun aplikasi web dan pengalaman digital kelas Awwwards dengan performa tinggi.',
            'meta_keywords' => 'Laravel, Tailwind, Fullstack Developer',
            'favicon_file' => $fakeFavicon,
            'robots_index' => '1',
            'twitter_handle' => '@wrwstudio',
            'schema_type' => 'Person',
        ])->assertRedirect();

        $this->assertEquals('Willy Rahmat Wijaya — Top Full-Stack Engineer', \App\Models\Profile::first()->seo['meta_title']);
        $this->assertNotNull(\App\Models\Profile::first()->seo['favicon']);

        // OWASP Grade A Security Headers assertions
        $home = $this->get('/');
        $home->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $home->assertHeader('X-Content-Type-Options', 'nosniff');
        $home->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $home->assertSee('rel="icon"', false);
        $home->assertSee('rel="apple-touch-icon"', false);
        $this->assertTrue($home->headers->has('Content-Security-Policy'));
        $this->assertTrue($home->headers->has('Permissions-Policy'));
        $this->assertFalse($home->headers->has('X-Powered-By'));

        // OWASP Top 10 Attack Probe Abort & IP Banned assertions
        $sqliProbe = $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.1'])->get('/?search=test%20UNION%20SELECT%201,2');
        $sqliProbe->assertStatus(403);

        $xssProbe = $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.2'])->get('/?q=%3Cscript%3Ealert(1)%3C/script%3E');
        $xssProbe->assertStatus(403);

        // Public testimonial submission
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->get('/testimoni')->assertStatus(200);

        $testiPost = $this->post('/testimoni', [
            'name' => 'Doni Kusuma',
            'role' => 'Pemilik Bisnis',
            'company' => 'Kopi Senja Utama',
            'quote' => 'Pelayanan sangat memuaskan, website toko online kami selesai tepat waktu dan hasilnya cepat sekali diakses pelanggan.',
            'rating' => 5,
        ]);
        $testiPost->assertRedirect();
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Doni Kusuma',
            'is_approved' => false,
        ]);

        // Admin moderation: toggle approval
        $createdTesti = \App\Models\Testimonial::where('name', 'Doni Kusuma')->first();
        $this->actingAs($user)->patch("/admin/testimonials/{$createdTesti->id}/toggle-approval")->assertRedirect();
        $this->assertTrue($createdTesti->fresh()->is_approved);
    }
}
