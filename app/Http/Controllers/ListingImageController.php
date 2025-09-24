<?php

namespace App\Http\Controllers;

use App\Models\ListingImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListingImageController extends Controller
{
    /**
     * Serve a stored listing image via the public disk.
     */
    public function show(ListingImage $image): StreamedResponse
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($image->filename)) {
            abort(404);
        }

        $stream = $disk->readStream($image->filename);

        if ($stream === false) {
            abort(404);
        }

        return response()->stream(function () use ($stream) {
            fpassthru($stream);

            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Cache-Control' => 'public, max-age='.(60 * 60 * 24 * 30),
            'Content-Type' => $disk->mimeType($image->filename) ?? 'image/jpeg',
        ]);
    }

    /**
     * Delete an image from a listing gallery.
     */
    public function destroy(ListingImage $image): RedirectResponse
    {
        $listing = $image->listing;

        if (! $listing) {
            abort(404);
        }

        if (Auth::id() !== $listing->user_id && ! Auth::user()?->is_admin) {
            abort(403, 'Nav atļauts dzēst šo bildi.');
        }

        Storage::disk('public')->delete($image->filename);
        $image->delete();

        return redirect()
            ->route('listings.edit', $listing)
            ->with('success', 'Bilde veiksmīgi dzēsta.');
    }
}
