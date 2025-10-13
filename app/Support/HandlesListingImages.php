<?php

namespace App\Support;

use App\Models\Listing;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait HandlesListingImages
{
    protected function storeListingImages(Listing $listing, iterable|UploadedFile|null $images): void
    {
        if ($images === null) {
            return;
        }

        if ($images instanceof UploadedFile) {
            $images = [$images];
        }

        $nextOrder = (int) $listing->galleryImages()->max('sort_order');

        foreach ($images as $image) {
            if (! $image instanceof UploadedFile || ! $image->isValid()) {
                continue;
            }

            $path = $this->compressAndStoreImage($image);
            $nextOrder++;

            $listing->galleryImages()->create([
                'filename' => $path,
                'sort_order' => $nextOrder,
            ]);
        }
    }

    protected function compressAndStoreImage(UploadedFile $image): string
    {
        if (! function_exists('imagecreatefromstring')) {
            return $this->storeOriginalImage($image);
        }

        $contents = @file_get_contents($image->getRealPath());

        if ($contents === false) {
            throw ValidationException::withMessages([
                'images' => 'Neizdevās nolasīt augšupielādēto bildi.',
            ]);
        }

        $resource = @imagecreatefromstring($contents);

        if ($resource === false) {
            return $this->storeOriginalImage($image);
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
                $stored = Storage::disk('public')->put($filename, $binary);

                if (! $stored) {
                    throw ValidationException::withMessages([
                        'images' => 'Neizdevās saglabāt apstrādāto bildi.',
                    ]);
                }

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
        $stored = Storage::disk('public')->put($filename, $binary);

        if (! $stored) {
            throw ValidationException::withMessages([
                'images' => 'Neizdevās saglabāt apstrādāto bildi.',
            ]);
        }

        return $filename;
    }

    protected function storeOriginalImage(UploadedFile $image): string
    {
        $disk = Storage::disk('public');

        $extension = strtolower($image->getClientOriginalExtension() ?: $image->guessExtension() ?: 'jpg');
        $filename = 'listings/' . Str::uuid() . '.' . $extension;

        $stream = fopen($image->getRealPath(), 'r');

        if ($stream === false) {
            throw ValidationException::withMessages([
                'images' => 'Neizdevās nolasīt augšupielādēto bildi.',
            ]);
        }

        try {
            $stored = $disk->put($filename, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        if (! $stored) {
            throw ValidationException::withMessages([
                'images' => 'Neizdevās saglabāt augšupielādēto bildi.',
            ]);
        }

        return $filename;
    }
}
