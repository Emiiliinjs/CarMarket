<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
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

        $report = $listing->reports()->create([
            'user_id' => $request->user()?->id,
            'reason' => $data['reason'],
        ]);

        AdminNotification::create([
            'type' => 'listing_reported',
            'title' => 'Ziņots sludinājums',
            'message' => sprintf(
                'Sludinājums "%s %s (%s)" tika ziņots ar iemeslu: %s',
                $listing->marka,
                $listing->modelis,
                $listing->gads,
                $report->reason
            ),
            'action_url' => route('admin.index', ['sekcija' => 'reports']) . '#reports',
        ]);

        return back()->with('success', 'Paldies! Ziņojums tika nosūtīts administratoram.');
    }
}
