<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()
            ->favoriteListings()
            ->whereHas('user', fn ($builder) => $builder->where('is_blocked', false))
            ->where('is_approved', true)
            ->latest('favorite_listings.created_at')
            ->paginate(9)
            ->withQueryString();

        $favoriteIds = $request->user()->favoriteListings()->pluck('listings.id')->all();

        return view('favorites.index', [
            'listings' => $favorites,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    public function store(Request $request, Listing $listing): RedirectResponse
    {
        if (! $listing->is_approved || $listing->user?->is_blocked) {
            return back()->with('error', 'Šo sludinājumu nevar pievienot favorītiem.');
        }

        $request->user()->favoriteListings()->syncWithoutDetaching($listing->id);

        return back()->with('success', 'Sludinājums pievienots favorītiem.');
    }

    public function destroy(Request $request, Listing $listing): RedirectResponse
    {
        $request->user()->favoriteListings()->detach($listing->id);

        return back()->with('success', 'Sludinājums izņemts no favorītiem.');
    }
}
