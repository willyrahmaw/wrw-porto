<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Console\Command;

class ConvertExistingImagesToWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portfolio:convert-existing-webp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert existing stored PNG/JPG assets to lightweight WebP format and update database references';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting image conversion to WebP...');

        // 1. Convert Profile photo if local PNG or JPG
        $profile = Profile::first();
        if ($profile && $profile->photo && str_starts_with($profile->photo, '/storage/')) {
            $newPhoto = $this->convertLocalImage($profile->photo);
            if ($newPhoto !== $profile->photo) {
                $profile->update(['photo' => $newPhoto]);
                $this->info("Updated Profile photo: {$profile->photo} -> {$newPhoto}");
            }
        }

        // 2. Convert Project thumbnails & galleries if local
        $projects = Project::all();
        foreach ($projects as $project) {
            $updated = false;
            if ($project->thumbnail && str_starts_with($project->thumbnail, '/storage/')) {
                $newThumb = $this->convertLocalImage($project->thumbnail);
                if ($newThumb !== $project->thumbnail) {
                    $project->thumbnail = $newThumb;
                    $updated = true;
                }
            }

            if (is_array($project->gallery)) {
                $newGallery = [];
                foreach ($project->gallery as $item) {
                    if (str_starts_with($item, '/storage/')) {
                        $newGallery[] = $this->convertLocalImage($item);
                    } else {
                        $newGallery[] = $item;
                    }
                }
                if ($newGallery !== $project->gallery) {
                    $project->gallery = $newGallery;
                    $updated = true;
                }
            }

            if ($updated) {
                $project->save();
                $this->info("Updated Project #{$project->id} images to WebP.");
            }
        }

        // 3. Convert Testimonial avatars if local
        $testimonials = Testimonial::all();
        foreach ($testimonials as $testimonial) {
            if ($testimonial->avatar && str_starts_with($testimonial->avatar, '/storage/')) {
                $newAvatar = $this->convertLocalImage($testimonial->avatar);
                if ($newAvatar !== $testimonial->avatar) {
                    $testimonial->update(['avatar' => $newAvatar]);
                    $this->info("Updated Testimonial #{$testimonial->id} avatar to WebP.");
                }
            }
        }

        $this->info('Image conversion completed successfully!');
        return 0;
    }

    private function convertLocalImage(string $storageUrl): string
    {
        // $storageUrl looks like "/storage/profile/abc.png"
        $relativePath = preg_replace('#^/storage/#', '', $storageUrl);
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!file_exists($fullPath)) {
            return $storageUrl;
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if ($extension === 'webp') {
            return $storageUrl; // Already WebP
        }

        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'bmp', 'gif'])) {
            return $storageUrl;
        }

        $image = match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($fullPath),
            'png' => @imagecreatefrompng($fullPath),
            'bmp' => @imagecreatefrombmp($fullPath),
            'gif' => @imagecreatefromgif($fullPath),
            default => null,
        };

        if (!$image) {
            return $storageUrl;
        }

        $destFullPath = preg_replace('/\.' . $extension . '$/i', '.webp', $fullPath);
        $destUrl = preg_replace('/\.' . $extension . '$/i', '.webp', $storageUrl);

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // Convert to WebP quality 82
        imagewebp($image, $destFullPath, 82);
        imagedestroy($image);

        $oldSize = filesize($fullPath);
        $newSize = filesize($destFullPath);
        $savedPercent = round((1 - ($newSize / max($oldSize, 1))) * 100, 1);

        $this->line("Converted [{$relativePath}]: " . round($oldSize / 1024) . " KB -> " . round($newSize / 1024) . " KB (-{$savedPercent}%)");

        return $destUrl;
    }
}

