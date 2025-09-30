<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Support\CarModelRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function __construct(
        private readonly CarModelRepository $carModels,
    ) {
    }

    // Rāda visus sludinājumus ar filtriem, kārtošanu un meklēšanu
    public function index(Request $request)
    {
        $filters = $request->only([
            'marka',
            'modelis',
            'price_min',
            'price_max',
            'year_from',
            'year_to',
            'degviela',
            'status',
            'search',
            'sort',
        ]);

        $query = Listing::query()
            ->approved()
            ->whereHas('user', fn ($builder) => $builder->where('is_blocked', false))
            ->filter($filters);

        $sort = $filters['sort'] ?? 'newest';
        $query = $this->applySorting($query, $sort);

        $listings = $query->paginate(9)->withQueryString();

        $favoriteIds = $request->user()
            ? $request->user()->favoriteListings()->pluck('listings.id')->all()
            : [];

        $fuelOptions = ['Benzīns', 'Dīzelis', 'Elektriska', 'Hibrīds'];

        $sortOptions = [
            'newest' => 'Jaunākie sludinājumi',
            'price_asc' => 'Cena: no zemākās',
            'price_desc' => 'Cena: no augstākās',
            'year_desc' => 'Gads: jaunākie',
            'year_asc' => 'Gads: vecākie',
        ];

        $statusOptions = [
            Listing::STATUS_AVAILABLE => 'Pieejams',
            Listing::STATUS_RESERVED => 'Rezervēts',
            Listing::STATUS_SOLD => 'Pārdots',
        ];

        return view('listings.index', [
            'listings'      => $listings,
            'filters'       => $filters,
            'sortOptions'   => $sortOptions,
            'statusOptions' => $statusOptions,
            'fuelOptions'   => $fuelOptions,
            'favoriteIds'   => $favoriteIds,
            'carModels'     => $this->carModels, // <<<< padod visu repository
        ]);
    }

    // Rāda konkrēta sludinājuma detaļas
    public function show(Listing $listing)
    {
        if (! $listing->is_approved && Auth::id() !== $listing->user_id && ! Auth::user()?->is_admin) {
            abort(404);
        }

        $isFavorite = Auth::check()
            ? Auth::user()->favoriteListings()->where('listings.id', $listing->id)->exists()
            : false;

        return view('listings.show', [
            'listing' => $listing,
            'isFavorite' => $isFavorite,
        ]);
    }

    public function liveBid(Listing $listing): View
    {
        if (! $listing->is_approved && Auth::id() !== $listing->user_id && ! Auth::user()?->is_admin) {
            abort(404);
        }

        $state = $listing->biddingState();

        return view('listings.bid', [
            'listing' => $listing,
            'minIncrement' => $state['minIncrement'],
            'currentBid' => $state['currentBid'],
            'nextBidAmount' => $state['nextBidAmount'],
            'recentBids' => $state['bids'],
        ]);
    }

    // Forma jaunam sludinājumam
    public function create()
    {
        return view('listings.create', [
            'carModels' => $this->carModels, // <<<< nevis carData
        ]);
    }

    // Saglabā jaunu sludinājumu
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
            'status' => 'required|in:' . implode(',', Listing::STATUSES),
            'contact_info' => 'nullable|string|max:1000',
            'show_contact' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['show_contact'] = $request->boolean('show_contact', false);
        $validated['is_approved'] = Auth::user()?->is_admin ?? false;
        unset($validated['images']);

        $listing = Listing::create($validated);

        if ($request->hasFile('images')) {
            $this->storeListingImages($listing, $request->file('images'));
        }

        return redirect()->route('listings.show', $listing->id)
            ->with('success', 'Sludinājums veiksmīgi pievienots!');
    }

    // Rediģēšanas forma
    public function edit(Listing $listing)
    {
        if (Auth::id() !== $listing->user_id && !Auth::user()?->is_admin) {
            abort(403, 'Nav atļauts rediģēt šo sludinājumu.');
        }

        return view('listings.edit', [
            'listing' => $listing,
            'carModels' => $this->carModels, // <<<<
        ]);
    }

    // Saglabā izmaiņas
    public function update(Request $request, Listing $listing)
    {
        if (Auth::id() !== $listing->user_id && !Auth::user()?->is_admin) {
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
            'status' => 'required|in:' . implode(',', Listing::STATUSES),
            'contact_info' => 'nullable|string|max:1000',
            'show_contact' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        unset($validated['images']);
        $validated['show_contact'] = $request->boolean('show_contact', false);
        if (! Auth::user()->is_admin) {
            $validated['is_approved'] = false;
        }

        $listing->update($validated);

        if ($request->hasFile('images')) {
            $this->storeListingImages($listing, $request->file('images'));
        }

        return redirect()->route('listings.show', $listing->id)
            ->with('success', 'Sludinājums veiksmīgi atjaunināts!');
    }

    // Dzēš sludinājumu
    public function destroy(Listing $listing)
    {
        if (Auth::id() !== $listing->user_id && !Auth::user()?->is_admin) {
            abort(403, 'Nav atļauts dzēst šo sludinājumu.');
        }

        foreach ($listing->galleryImages as $image) {
            \Storage::disk('public')->delete($image->filename);
            $image->delete();
        }

        $listing->delete();

        return redirect()->route('listings.index')
            ->with('success', 'Sludinājums veiksmīgi dzēsts!');
    }

    public function myListings(Request $request)
    {
        $filters = $request->only([
            'marka',
            'modelis',
            'status',
            'search',
        ]);

        $query = $request->user()->listings()->with(['galleryImages'])->filter($filters);

        $sort = $request->input('sort', 'newest');
        $query = $this->applySorting($query, $sort);

        $listings = $query->paginate(9)->withQueryString();

        $statusOptions = [
            Listing::STATUS_AVAILABLE => 'Pieejams',
            Listing::STATUS_RESERVED => 'Rezervēts',
            Listing::STATUS_SOLD => 'Pārdots',
        ];

        return view('listings.my', [
            'listings'      => $listings,
            'filters'       => $filters,
            'statusOptions' => $statusOptions,
            'favoriteIds'   => $request->user()->favoriteListings()->pluck('listings.id')->all(),
            'carModels'     => $this->carModels, // <<<<
        ]);
    }

    private function applySorting($query, string $sort)
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('cena', 'asc'),
            'price_desc' => $query->orderBy('cena', 'desc'),
            'year_asc' => $query->orderBy('gads', 'asc'),
            'year_desc' => $query->orderBy('gads', 'desc'),
            default => $query->latest(),
        };
    }

    private function storeListingImages(Listing $listing, array $images): void
    {
        foreach ($images as $image) {
            if (! $image instanceof UploadedFile || ! $image->isValid()) {
                continue;
            }

            $path = $this->compressAndStoreImage($image);
            $listing->galleryImages()->create(['filename' => $path]);
        }
    }

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
