<?php

namespace App\Support;

use App\Models\Listing;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait HandlesListingImages
{
    protected function storeListingImages(Listing $listing, array $images): void
    {
        foreach ($images as $image) {
            if (! $image instanceof UploadedFile || ! $image->isValid()) {
                continue;
            }

            $path = $this->compressAndStoreImage($image);
            $listing->galleryImages()->create(['filename' => $path]);
        }
    }

    protected function compressAndStoreImage(UploadedFile $image): string
    {
        $resource = @imagecreatefromstring(file_get_contents($image->getRealPath()));

        if ($resource === false) {
            throw ValidationException::withMessages([
                'images' => 'Neizdevās apstrādāt vienu no augšupielādētajām bildēm.',
            ]);
        }

        if (function_exists('imagepalettetotruecolor') && ! imageistruecolor($resource)) {
            imagepalettetotruecolor($resource);
        }

        imagealphablending($resource, false);
        imagesavealpha($resource, true);

        $resource = $this->resizeImage($resource, 1600, 1600);
        imageinterlace($resource, true);

        $path = $this->encodeAndStore($resource);

        imagedestroy($resource);

        return $path;
    }

    /**
     * @param resource $resource
     * @return resource
     */
    protected function resizeImage($resource, int $maxWidth, int $maxHeight)
    {
        $width = imagesx($resource);
        $height = imagesy($resource);

        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);

        if ($ratio >= 1) {
            return $resource;
        }

        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled($resized, $resource, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($resource);

        return $resized;
    }

    /**
     * @param resource $resource
     */
    protected function encodeAndStore($resource): string
    {
        if (function_exists('imagewebp')) {
            ob_start();
            $encoded = imagewebp($resource, null, 80);
            $binary = ob_get_clean();

            if ($encoded && $binary !== false) {
                $filename = 'listings/' . Str::uuid() . '.webp';
                Storage::disk('public')->put($filename, $binary);

                return $filename;
            }
        }

        ob_start();
        imagepng($resource, null, 6);
        $binary = ob_get_clean();

        if ($binary === false) {
            throw ValidationException::withMessages([
                'images' => 'Neizdevās saglabāt apstrādāto bildi.',
            ]);
        }

        $filename = 'listings/' . Str::uuid() . '.png';
        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }
}
