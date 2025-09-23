<?php

namespace App\Http\Controllers;

use App\Models\ListingImage;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListingImageController extends Controller
{
    /**
     * Serve a stored listing image via the public disk.
     */
    public function show(ListingImage $image): BinaryFileResponse
    {
        if (! Storage::disk('public')->exists($image->filename)) {
            abort(404);
        }

        $path = Storage::disk('public')->path($image->filename);

        return response()->file($path, [
            'Cache-Control' => 'public, max-age='.(60 * 60 * 24 * 30),
        ]);
    }
}
