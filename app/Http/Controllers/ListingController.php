<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Listing;
use App\Support\CarModelRepository;
use App\Support\HandlesListingImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ListingController extends Controller
{
    use HandlesListingImages;

    /**
     * Rāda visus sludinājumus ar filtriem un kārtošanu
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'marka','modelis','price_min','price_max',
            'year_from','year_to','degviela','status',
            'search','sort',
        ]);

        $query = Listing::query()
            ->approved()
            ->where('is_admin_bidding', false)
            ->whereHas('user', fn ($q) => $q->where('is_blocked', false))
            ->filter($filters);

        $sort = $filters['sort'] ?? 'newest';
        $query = $this->applySorting($query, $sort);

        $listings = $query->paginate(9)->withQueryString();

        // Tikai markas + modeļi, kas reāli eksistē DB
        $carModels = Listing::query()
            ->select('marka', 'modelis')
            ->where('is_admin_bidding', false)
            ->whereNotNull('marka')
            ->whereNotNull('modelis')
            ->get()
            ->groupBy('marka')
            ->map(fn ($group) => $group->pluck('modelis')->unique()->values())
            ->sortKeys();

        return view('listings.index', [
            'listings'      => $listings,
            'filters'       => $filters,
            'sortOptions'   => [
                'newest'     => 'Jaunākie sludinājumi',
                'price_asc'  => 'Cena: no zemākās',
                'price_desc' => 'Cena: no augstākās',
                'year_desc'  => 'Gads: jaunākie',
                'year_asc'   => 'Gads: vecākie',
            ],
            'statusOptions' => [
                Listing::STATUS_AVAILABLE => 'Pieejams',
                Listing::STATUS_RESERVED  => 'Rezervēts',
                Listing::STATUS_SOLD      => 'Pārdots',
            ],
            'fuelOptions'   => ['Benzīns','Dīzelis','Elektriska','Hibrīds'],
            'favoriteIds'   => $request->user()
                ? $request->user()->favoriteListings()->pluck('listings.id')->all()
                : [],
            'carModels'     => $carModels,
        ]);
    }

    /**
     * Rāda konkrētu sludinājumu
     */
    public function show(Listing $listing)
    {
        if ($listing->is_admin_bidding && ! Auth::user()?->is_admin) {
            abort(404);
        }

        if (! $listing->is_approved && Auth::id() !== $listing->user_id && ! Auth::user()?->is_admin) {
            abort(404);
        }

        $isFavorite = Auth::check()
            ? Auth::user()->favoriteListings()->where('listings.id',$listing->id)->exists()
            : false;

        return view('listings.show', compact('listing','isFavorite'));
    }

    /**
     * Rāda "dzīvās izsoles" skatu (tikai adminam)
     */
    public function liveBid(Listing $listing): View
    {
        if (! $listing->is_admin_bidding) {
            abort(404);
        }

        if (! Auth::user()?->is_admin) {
            abort(403);
        }

        if (! $listing->is_approved && Auth::id() !== $listing->user_id && ! Auth::user()?->is_admin) {
            abort(404);
        }

        $state = $listing->biddingState();

        return view('listings.bid', [
            'listing'       => $listing,
            'minIncrement'  => $state['minIncrement'],
            'currentBid'    => $state['currentBid'],
            'nextBidAmount' => $state['nextBidAmount'],
            'recentBids'    => $state['bids'],
        ]);
    }

    /**
     * Forma jauna sludinājuma izveidei
     */
    public function create(CarModelRepository $carModels)
    {
        return view('listings.create', [
            'carModels' => $carModels->all(),
        ]);
    }

    /**
     * Saglabā jaunu sludinājumu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'marka'         => 'required|string|max:255',
            'modelis'       => 'required|string|max:255',
            'gads'          => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'nobraukums'    => 'required|integer|min:0',
            'cena'          => 'required|numeric|min:0',
            'degviela'      => 'required|string|max:50',
            'parnesumkarba' => 'required|string|max:50',

            // jaunie lauki
            'motora_tilpums'   => 'nullable|numeric|min:0|max:9.9',
            'virsbuves_tips'   => 'nullable|string|max:50',
            'vin_numurs'       => 'nullable|string|max:50',
            'valsts_numurzime' => 'nullable|string|max:50',
            'tehniska_apskate' => 'nullable|date',

            'apraksts'      => 'nullable|string',
            'status'        => 'required|in:'.implode(',', Listing::STATUSES),
            'contact_info'  => 'nullable|string|max:1000',
            'show_contact'  => 'nullable|boolean',

            'images'   => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $validated['user_id']      = Auth::id();
        $validated['show_contact'] = $request->boolean('show_contact', false);
        $validated['is_approved']  = Auth::user()?->is_admin ?? false;

        // šos atstāj ārpus mass-assign if validated satur images atslēgu
        unset($validated['images']);

        $listing = Listing::create($validated);

        // bildes
        $this->storeListingImages($listing, $request->file('images'));

        if (! $listing->is_approved) {
            AdminNotification::create([
                'type' => 'listing_pending',
                'title' => 'Jauns sludinājums gaida apstiprinājumu',
                'message' => sprintf(
                    '%s %s (%s) tika iesniegts un gaida administratora apstiprinājumu.',
                    $listing->marka,
                    $listing->modelis,
                    $listing->gads
                ),
                'action_url' => route('admin.index', ['sekcija' => 'pending-listings']) . '#pending-listings',
            ]);
        }

        return redirect()->route('listings.show',$listing->id)
            ->with('success','Sludinājums veiksmīgi pievienots!');
    }

    /**
     * Forma esoša sludinājuma labošana
     */
    public function edit(Listing $listing, CarModelRepository $carModels)
    {
        if (Auth::id() !== $listing->user_id && !Auth::user()?->is_admin) {
            abort(403,'Nav atļauts rediģēt šo sludinājumu.');
        }

        return view('listings.edit', [
            'listing'   => $listing,
            'carModels' => $carModels->all(),
        ]);
    }

    /**
     * Atjauno esošu sludinājumu
     */
    public function update(Request $request, Listing $listing)
    {
        if (Auth::id() !== $listing->user_id && !Auth::user()?->is_admin) {
            abort(403,'Nav atļauts rediģēt šo sludinājumu.');
        }

        $validated = $request->validate([
            'marka'         => 'required|string|max:255',
            'modelis'       => 'required|string|max:255',
            'gads'          => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'nobraukums'    => 'required|integer|min:0',
            'cena'          => 'required|numeric|min:0',
            'degviela'      => 'required|string|max:50',
            'parnesumkarba' => 'required|string|max:50',

            // jaunie lauki
            'motora_tilpums'   => 'nullable|numeric|min:0|max:9.9',
            'virsbuves_tips'   => 'nullable|string|max:50',
            'vin_numurs'       => 'nullable|string|max:50',
            'valsts_numurzime' => 'nullable|string|max:50',
            'tehniska_apskate' => 'nullable|date',

            'apraksts'      => 'nullable|string',
            'status'        => 'required|in:'.implode(',', Listing::STATUSES),
            'contact_info'  => 'nullable|string|max:1000',
            'show_contact'  => 'nullable|boolean',

            'images'                 => 'nullable|array',
            'images.*'               => 'image|max:2048',
            'remove_images'          => 'nullable|array',
            'existing_image_order'   => 'nullable|array',
            'existing_image_order.*' => [
                'integer',
                Rule::exists('listing_images', 'id')->where('listing_id', $listing->id),
            ],
        ]);

        // neatjauninām šīs atslēgas tieši uz modeli
        unset($validated['images'],$validated['remove_images'],$validated['existing_image_order']);

        $validated['show_contact'] = $request->boolean('show_contact', false);

        // parasts lietotājs ar update -> atkārtoti vajag apstiprinājumu
        if (!Auth::user()->is_admin) {
            $validated['is_approved'] = false;
        }

        $listing->update($validated);

        // Dzēst atzīmētās bildes
        if ($request->filled('remove_images')) {
            foreach ($request->input('remove_images') as $imageId) {
                $img = $listing->galleryImages()->find($imageId);
                if ($img) {
                    $img->delete();
                }
            }
        }

        $listing->load('galleryImages');

        // Kārtība esošajām bildēm
        $order = $request->input('existing_image_order', []);
        if (! empty($order)) {
            $position = 0;
            foreach (array_values(array_unique($order)) as $imageId) {
                $img = $listing->galleryImages()->find($imageId);
                if (! $img) {
                    continue;
                }
                $position++;
                $img->update(['sort_order' => $position]);
            }
        } elseif ($listing->galleryImages->isNotEmpty()) {
            $position = 0;
            foreach ($listing->galleryImages as $img) {
                $position++;
                if ($img->sort_order !== $position) {
                    $img->update(['sort_order' => $position]);
                }
            }
        }

        // Pievienot jaunas bildes
        $this->storeListingImages($listing, $request->file('images'));

        return redirect()->route('listings.show',$listing->id)
            ->with('success','Sludinājums veiksmīgi atjaunināts!');
    }

    /**
     * Dzēš sludinājumu
     */
    public function destroy(Listing $listing)
    {
        if (Auth::id() !== $listing->user_id && !Auth::user()?->is_admin) {
            abort(403,'Nav atļauts dzēst šo sludinājumu.');
        }

        $listing->delete();

        return redirect()->route('listings.index')
            ->with('success','Sludinājums veiksmīgi dzēsts!');
    }

    /**
     * Rāda lietotāja sludinājumus
     */
    public function myListings(Request $request)
    {
        $filters = $request->only(['marka','modelis','status','search']);
        $query   = $request->user()->listings()
            ->where('is_admin_bidding', false)
            ->with('galleryImages')
            ->filter($filters);

        $sort = $request->input('sort','newest');
        $query = $this->applySorting($query,$sort);

        $listings = $query->paginate(9)->withQueryString();

        // Tikai markas + modeļi no šī lietotāja sludinājumiem
        $carModels = $request->user()->listings()
            ->where('is_admin_bidding', false)
            ->select('marka','modelis')
            ->distinct()
            ->get()
            ->groupBy('marka')
            ->map(fn($items) => $items->pluck('modelis')->unique()->values());

        return view('listings.my', [
            'listings'      => $listings,
            'filters'       => $filters,
            'statusOptions' => [
                Listing::STATUS_AVAILABLE => 'Pieejams',
                Listing::STATUS_RESERVED  => 'Rezervēts',
                Listing::STATUS_SOLD      => 'Pārdots',
            ],
            'favoriteIds'   => $request->user()->favoriteListings()->pluck('listings.id')->all(),
            'carModels'     => $carModels,
        ]);
    }

    /**
     * Palīgmetode kārtošanai
     */
    private function applySorting($query,string $sort)
    {
        return match($sort){
            'price_asc'  => $query->orderBy('cena','asc'),
            'price_desc' => $query->orderBy('cena','desc'),
            'year_asc'   => $query->orderBy('gads','asc'),
            'year_desc'  => $query->orderBy('gads','desc'),
            default      => $query->latest(),
        };
    }
}
