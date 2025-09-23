<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'images.*' => 'image|max:2048', // katra bilde max 2MB
        ]);

        $validated['user_id'] = Auth::id();

        $listing = Listing::create($validated);

        // Saglabā bildes, ja augšupielādētas
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('listings', 'public');
                $listing->images()->create(['filename' => $path]);
            }
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
            'images.*' => 'image|max:2048',
        ]);

        $listing->update($validated);

        // Saglabā jaunas bildes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('listings', 'public');
                $listing->images()->create(['filename' => $path]);
            }
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
}
