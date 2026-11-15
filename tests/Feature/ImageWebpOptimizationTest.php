<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\ImageOptimizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageWebpOptimizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_optimizer_service_converts_uploaded_image_to_webp(): void
    {
        Storage::fake('public');

        // Create a fake PNG image
        $file = UploadedFile::fake()->image('test_avatar.png', 800, 600);

        $storedPath = ImageOptimizerService::convertAndStore($file, 'avatars');

        // Verify return format is /storage/avatars/{hash}.webp
        $this->assertStringStartsWith('/storage/avatars/', $storedPath);
        $this->assertStringEndsWith('.webp', $storedPath);

        // Verify the file was created in the storage public path
        $relativeDiskPath = str_replace('/storage/', '', $storedPath);
        $fullPath = storage_path('app/public/' . $relativeDiskPath);
        $this->assertFileExists($fullPath);

        // Verify it is a valid WebP file
        $info = @getimagesize($fullPath);
        $this->assertIsArray($info);
        $this->assertEquals('image/webp', $info['mime']);
    }

    public function test_profile_photo_upload_is_converted_to_webp(): void
    {
        $this->seed();
        $user = User::first();
        $profile = Profile::first();

        $uploadedPhoto = UploadedFile::fake()->image('my_profile.jpg', 600, 600);

        $response = $this->actingAs($user)->put('/admin/profile', [
            'name' => $profile->name,
            'initials' => $profile->initials,
            'profession' => $profile->profession,
            'tagline' => $profile->tagline,
            'bio' => $profile->bio,
            'location' => $profile->location,
            'availability_status' => $profile->availability_status,
            'email' => $profile->email,
            'whatsapp' => $profile->whatsapp,
            'trusted_clients_count' => 15,
            'trusted_industries_count' => 6,
            'photo_file' => $uploadedPhoto,
        ]);

        $response->assertRedirect();

        $updatedProfile = Profile::first();
        $this->assertStringEndsWith('.webp', $updatedProfile->photo);
        $this->assertStringStartsWith('/storage/profile/', $updatedProfile->photo);
    }

    public function test_project_thumbnail_and_gallery_upload_are_converted_to_webp(): void
    {
        $this->seed();
        $user = User::first();

        $thumbnail = UploadedFile::fake()->image('thumbnail.png', 1200, 800);
        $galleryItem1 = UploadedFile::fake()->image('screenshot1.jpg', 1600, 900);
        $galleryItem2 = UploadedFile::fake()->image('screenshot2.png', 1600, 900);

        $response = $this->actingAs($user)->post('/admin/projects', [
            'title' => 'E-Commerce Platform WebP',
            'category' => 'Web Application',
            'year' => '2026',
            'description' => 'Test project with WebP converted media uploads.',
            'thumbnail_file' => $thumbnail,
            'gallery_files' => [$galleryItem1, $galleryItem2],
        ]);

        $response->assertRedirect();

        $project = Project::where('title', 'E-Commerce Platform WebP')->first();
        $this->assertNotNull($project);
        $this->assertStringEndsWith('.webp', $project->thumbnail);
        $this->assertStringStartsWith('/storage/projects/', $project->thumbnail);

        $this->assertIsArray($project->gallery);
        $this->assertCount(2, $project->gallery);
        foreach ($project->gallery as $item) {
            $this->assertStringEndsWith('.webp', $item);
            $this->assertStringStartsWith('/storage/projects/gallery/', $item);
        }
    }

    public function test_public_testimonial_avatar_upload_is_converted_to_webp(): void
    {
        $avatarFile = UploadedFile::fake()->image('client_avatar.png', 400, 400);

        $response = $this->post('/testimoni', [
            'name' => 'Siti Nurhaliza',
            'role' => 'Marketing Lead',
            'company' => 'Brand Lokal Bersama',
            'quote' => 'Kerja sama yang sangat memuaskan, desain bersih dan performa website meningkat drastis.',
            'rating' => 5,
            'avatar_file' => $avatarFile,
        ]);

        $response->assertRedirect();

        $testimonial = Testimonial::where('name', 'Siti Nurhaliza')->first();
        $this->assertNotNull($testimonial);
        $this->assertNotNull($testimonial->avatar);
        $this->assertStringEndsWith('.webp', $testimonial->avatar);
        $this->assertStringStartsWith('/storage/testimonials/', $testimonial->avatar);
    }
}

