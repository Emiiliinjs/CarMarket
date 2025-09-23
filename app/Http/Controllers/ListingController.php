<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ListingController extends Controller
{
    // Rāda visus sludinājumus ar pagination
    public function index()
    {
        $listings = Listing::latest()->paginate(9);
        return view('listings.index', compact('listings'));
    }

    // Rāda konkrēta sludinājuma detaļas
    public function show(Listing $listing)
    {
        return view('listings.show', compact('listing'));
    }

    // Forma jaunam sludinājumam
    public function create()
    {
        return view('listings.create');
    }

    // Saglabā jaunu sludinājumu ar bildēm
    public function store(Request $request)
    {
        $validated = $request->validate([
            'marka' => 'required|string|max:255',
            'modelis' => 'required|string|max:255',
            'gads' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'nobraukums' => 'required|integer|min:0',
            'cena' => 'required|numeric|min:0',
            'degviela' => 'required|string',
            'parnesumkarba' => 'required|string',
            'apraksts' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048', // katra bilde max 2MB
        ]);

        $validated['user_id'] = Auth::id();
        unset($validated['images']);

        $listing = Listing::create($validated);

        // Saglabā bildes, ja augšupielādētas
        if ($request->hasFile('images')) {
            $this->storeListingImages($listing, $request->file('images'));
        }

        return redirect()->route('listings.show', $listing->id)
                         ->with('success', 'Sludinājums veiksmīgi pievienots!');
    }

    // Rediģēšanas forma
    public function edit(Listing $listing)
    {
        // Atļauj tikai autoram vai adminam
        if (Auth::id() !== $listing->user_id && !Auth::user()->is_admin) {
            abort(403, 'Nav atļauts rediģēt šo sludinājumu.');
        }

        return view('listings.edit', compact('listing'));
    }

    // Saglabā izmaiņas
    public function update(Request $request, Listing $listing)
    {
        if (Auth::id() !== $listing->user_id && !Auth::user()->is_admin) {
            abort(403, 'Nav atļauts rediģēt šo sludinājumu.');
        }

        $validated = $request->validate([
            'marka' => 'required|string|max:255',
            'modelis' => 'required|string|max:255',
            'gads' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'nobraukums' => 'required|integer|min:0',
            'cena' => 'required|numeric|min:0',
            'degviela' => 'required|string',
            'parnesumkarba' => 'required|string',
            'apraksts' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        unset($validated['images']);

        $listing->update($validated);

        // Saglabā jaunas bildes
        if ($request->hasFile('images')) {
            $this->storeListingImages($listing, $request->file('images'));
        }

        return redirect()->route('listings.show', $listing->id)
                         ->with('success', 'Sludinājums veiksmīgi atjaunināts!');
    }

    // Dzēst sludinājumu
    public function destroy(Listing $listing)
    {
        if (Auth::id() !== $listing->user_id && !Auth::user()->is_admin) {
            abort(403, 'Nav atļauts dzēst šo sludinājumu.');
        }

        // Dzēst arī bildes no storage
        foreach ($listing->images as $image) {
            \Storage::disk('public')->delete($image->filename);
            $image->delete();
        }

        $listing->delete();

        return redirect()->route('listings.index')
                         ->with('success', 'Sludinājums veiksmīgi dzēsts!');
}

    /**
     * Kompresē un saglabā augšupielādētās bildes vienotā kvalitātē.
     */
    private function storeListingImages(Listing $listing, array $images): void
    {
        foreach ($images as $image) {
            if (! $image instanceof UploadedFile || ! $image->isValid()) {
                continue;
            }

            $path = $this->compressAndStoreImage($image);
            $listing->images()->create(['filename' => $path]);
        }
    }

    /**
     * Samazina bildes izmērus, kompresē un saglabā to Storage mapē.
     */
    private function compressAndStoreImage(UploadedFile $image): string
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
     * Pielāgo bildes izmērus, nepārsniedzot norādītos maksimālos izmērus.
     *
     * @param  resource  $resource
     * @return resource
     */
    private function resizeImage($resource, int $maxWidth, int $maxHeight)
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
     * Kodē bildi WebP formātā (vai PNG rezerves gadījumā) un saglabā Storage diskā.
     *
     * @param  resource  $resource
     */
    private function encodeAndStore($resource): string
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
