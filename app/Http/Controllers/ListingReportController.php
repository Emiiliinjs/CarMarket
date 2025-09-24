<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ListingReportController extends Controller
{
    public function store(Request $request, Listing $listing): RedirectResponse
    {
        $data = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $listing->reports()->create([
            'user_id' => $request->user()?->id,
            'reason' => $data['reason'],
        ]);

        return back()->with('success', 'Paldies! Ziņojums tika nosūtīts administratoram.');
    }
}
