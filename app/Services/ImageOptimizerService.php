<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    /**
     * Store and automatically convert an uploaded image to lightweight WebP format.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory Target directory inside storage/app/public
     * @param int $quality WebP quality (1-100), default 82 (sweet spot: visually lossless & lightweight)
     * @param int|null $maxDimension Max width/height to downscale large camera photos
     * @return string Relative storage URL, e.g. "/storage/profile/random.webp"
     */
    public static function convertAndStore(
        UploadedFile $file,
        string $directory = 'uploads',
        int $quality = 82,
        ?int $maxDimension = 2000
    ): string {
        $realPath = $file->getRealPath();
        $mime = $file->getMimeType();

        // If SVG or GD extension is not loaded, fallback to regular upload
        if ($mime === 'image/svg+xml' || !extension_loaded('gd')) {
            $path = $file->store($directory, 'public');
            return '/storage/' . $path;
        }

        // Create GD image resource based on mime type
        $image = null;
        try {
            $image = match ($mime) {
                'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($realPath),
                'image/png' => @imagecreatefrompng($realPath),
                'image/webp' => @imagecreatefromwebp($realPath),
                'image/gif' => @imagecreatefromgif($realPath),
                'image/bmp', 'image/x-ms-bmp' => @imagecreatefrombmp($realPath),
                default => null,
            };
        } catch (\Throwable) {
            $image = null;
        }

        // If failed to read image, fallback to standard store
        if (!$image) {
            $path = $file->store($directory, 'public');
            return '/storage/' . $path;
        }

        // Auto-fix EXIF orientation if JPEG
        if (function_exists('exif_read_data') && ($mime === 'image/jpeg' || $mime === 'image/jpg')) {
            try {
                $exif = @exif_read_data($realPath);
                if (!empty($exif['Orientation'])) {
                    $image = match ($exif['Orientation']) {
                        3 => imagerotate($image, 180, 0),
                        6 => imagerotate($image, -90, 0),
                        8 => imagerotate($image, 90, 0),
                        default => $image,
                    };
                }
            } catch (\Throwable) {
                // Ignore EXIF errors
            }
        }

        $origWidth = imagesx($image);
        $origHeight = imagesy($image);

        // Downscale oversized photos proportionally to preserve performance
        if ($maxDimension && ($origWidth > $maxDimension || $origHeight > $maxDimension)) {
            $ratio = min($maxDimension / $origWidth, $maxDimension / $origHeight);
            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($image);
            $image = $resized;
        } else {
            imagepalettetotruecolor($image);
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        // Prepare destination path in storage/app/public/{directory}
        $filename = Str::random(40) . '.webp';
        $relativeDir = trim($directory, '/');
        $fullDirPath = storage_path('app/public/' . $relativeDir);

        if (!is_dir($fullDirPath)) {
            mkdir($fullDirPath, 0755, true);
        }

        $fullPath = $fullDirPath . '/' . $filename;

        // Save as WebP
        imagewebp($image, $fullPath, $quality);
        imagedestroy($image);

        return '/storage/' . $relativeDir . '/' . $filename;
    }
}
