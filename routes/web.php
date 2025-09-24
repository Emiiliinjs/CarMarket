<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ListingImageController;
use App\Http\Controllers\ListingReportController;
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
Route::middleware(['auth', 'active-user'])->group(function () {
    // Create & Store
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');

    // Edit, Update, Destroy
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');

    Route::delete('/listing-images/{image}', [ListingImageController::class, 'destroy'])->name('listing-images.destroy');

    Route::get('/my-listings', [ListingController::class, 'myListings'])->name('listings.mine');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{listing}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{listing}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

// Sludinājuma detaļas (skatīt jebkurš) — jābūt **pēc create/edit maršrutiem**
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

Route::post('/listings/{listing}/report', [ListingReportController::class, 'store'])->name('listings.report');

Route::middleware(['auth', 'active-user', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/listings/{listing}/approve', [AdminController::class, 'approveListing'])->name('listings.approve');
    Route::delete('/listings/{listing}', [AdminController::class, 'destroyListing'])->name('listings.destroy');
    Route::post('/users/{user}/toggle-block', [AdminController::class, 'toggleUserBlock'])->name('users.toggle-block');
    Route::post('/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('reports.resolve');
});

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
Route::middleware(['auth', 'active-user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
