<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Support\CarModelRepository;
use App\Support\HandlesListingImages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminBiddingController extends Controller
{
    use HandlesListingImages;

    public function index(): View
    {
        $listings = Listing::adminBidding()
            ->with('galleryImages')
            ->latest()
            ->get();

        return view('admin.bidding.index', [
            'listings' => $listings,
        ]);
    }

    public function create(CarModelRepository $carModels): View
    {
        return view('admin.bidding.create', [
            'statuses' => Listing::STATUSES,
            'carModels' => $carModels->all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'marka'        => 'required|string|max:255',
            'modelis'      => 'required|string|max:255',
            'gads'         => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'nobraukums'   => 'required|integer|min:0',
            'cena'         => 'required|numeric|min:0',
            'degviela'     => 'required|string|max:255',
            'parnesumkarba'=> 'required|string|max:255',
            'apraksts'     => 'nullable|string',
            'status'       => 'required|in:' . implode(',', Listing::STATUSES),
            'contact_info' => 'nullable|string|max:1000',
            'show_contact' => 'nullable|boolean',
            'images'       => 'nullable|array',
            'images.*'     => 'image|max:2048',
        ]);

        $allowedModels = app(CarModelRepository::class)->all();
        $brandModels = $allowedModels[$validated['marka']] ?? [];

        if (! array_key_exists($validated['marka'], $allowedModels) || ! in_array($validated['modelis'], $brandModels, true)) {
            return back()
                ->withErrors([
                    'modelis' => 'Izvēlies auto marku un modeli no cardata.json saraksta.',
                ])
                ->withInput();
        }

        $validated['user_id'] = Auth::id();
        $validated['show_contact'] = $request->boolean('show_contact', false);
        $validated['is_approved'] = true;
        $validated['is_admin_bidding'] = true;
        unset($validated['images']);

        $listing = Listing::create($validated);

        $this->storeListingImages($listing, $request->file('images'));

        return redirect()
            ->route('admin.bidding.index')
            ->with('success', 'Izsoles sludinājums veiksmīgi izveidots.');
    }

    public function destroy(Listing $listing): RedirectResponse
    {
        if (! $listing->is_admin_bidding) {
            abort(404);
        }

        $listing->delete();

        return redirect()
            ->route('admin.bidding.index')
            ->with('success', 'Izsoles sludinājums dzēsts.');
    }
}
