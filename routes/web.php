<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\ListingImageController;
use App\Http\Controllers\ProfileController;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Mājaslapa - rāda visus sludinājumus
Route::get('/', [ListingController::class, 'index'])->name('listings.index');

// Galerijas bilžu apkalpošana
Route::get('/listing-images/{image}', [ListingImageController::class, 'show'])
    ->name('listing-images.show');

// Sludinājumu CRUD (auth required)
Route::middleware('auth')->group(function () {
    // Create & Store
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');

    // Edit, Update, Destroy
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
});

// Sludinājuma detaļas (skatīt jebkurš) — jābūt **pēc create/edit maršrutiem**
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

// Dashboard
Route::get('/dashboard', function () {
    $stats = [
        'listingCount' => Listing::count(),
        'userCount' => User::count(),
        'averagePrice' => Listing::avg('cena'),
        'totalImageCount' => ListingImage::count(),
        'listingsThisMonth' => Listing::where('created_at', '>=', now()->startOfMonth())->count(),
        'newUsersThisMonth' => User::where('created_at', '>=', now()->startOfMonth())->count(),
        'latestListings' => Listing::latest()->take(5)->get(),
    ];

    return view('dashboard', $stats);
})->middleware(['auth', 'verified'])->name('dashboard');

// Profila CRUD (Laravel Breeze default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
