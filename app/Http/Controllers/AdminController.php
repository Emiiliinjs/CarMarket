<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingReport;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $pendingListings = Listing::where('is_approved', false)
            ->with(['user', 'galleryImages'])
            ->latest()
            ->get();

        $reports = ListingReport::where('status', 'open')
            ->with(['listing', 'user'])
            ->latest()
            ->get();

        $blockedUsers = User::where('is_blocked', true)->get();

        return view('admin.index', [
            'pendingListings' => $pendingListings,
            'reports' => $reports,
            'blockedUsers' => $blockedUsers,
        ]);
    }

    public function approveListing(Listing $listing): RedirectResponse
    {
        $listing->update(['is_approved' => true]);

        return back()->with('success', 'Sludinājums apstiprināts.');
    }

    public function toggleUserBlock(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return back()->with('error', 'Administrators nav bloķējams.');
        }

        $user->update(['is_blocked' => ! $user->is_blocked]);

        return back()->with('success', $user->is_blocked ? 'Lietotājs bloķēts.' : 'Lietotājs atbloķēts.');
    }

    public function resolveReport(ListingReport $report): RedirectResponse
    {
        $report->update(['status' => 'resolved']);

        return back()->with('success', 'Ziņojums atzīmēts kā atrisināts.');
    }

    public function destroyListing(Listing $listing): RedirectResponse
    {
        $listing->delete();

        return back()->with('success', 'Sludinājums dzēsts.');
    }
}
