<?php

namespace App\Http\Controllers;

use App\Models\ListingImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
