<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::latest()->paginate(9);
        return view('listings.index', compact('listings'));
    }

    public function show(Listing $listing)
    {
        return view('listings.show', compact('listing'));
    }

    public function create()
    {
        return view('listings.create');
    }

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
        ]);

        $validated['user_id'] = Auth::id();

        Listing::create($validated);

        return redirect()->route('listings.index')->with('success', 'Sludinājums veiksmīgi pievienots!');
    }
}
