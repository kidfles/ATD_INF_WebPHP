<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AdvertisementController;
use Illuminate\Support\Facades\Route;

// ZONE A: Public Market
Route::get('/test', function () {
    return 'Test OK';
});

Route::get('/create-test-auction', function () {
    $user = \App\Models\User::factory()->create();
    $ad = \App\Models\Advertisement::factory()->create([
        'user_id' => $user->id,
        'type' => 'auction',
        'title' => 'Test Auction Item',
        'description' => 'This is a test auction item created for you to bid on.',
        'price' => 10.00,
    ]);
    return redirect()->route('market.show', $ad);
});

Route::get('/', function () {
    $featuredAds = \App\Models\Advertisement::latest()->take(6)->get();
    return view('pages.home', compact('featuredAds'));
})->name('home');

Route::get('/market', [MarketController::class, 'index'])->name('market.index');
Route::get('/market/{advertisement}', [MarketController::class, 'show'])->name('market.show');

// ZONE C: Whitelabel Company Pages
Route::get('/company/{company:slug}', [CompanyController::class, 'show'])->name('company.show');

// ZONE B: Dashboard (Secure)
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', function () {
        return view('pages.dashboard.index');
    })->name('index');

    Route::get('/rentals', [\App\Http\Controllers\RentalController::class, 'index'])->name('rentals.index');
    Route::resource('advertisements', AdvertisementController::class);
    
    // Future: Rentals, Favorites, etc.
});

Route::middleware('auth')->group(function () {
    Route::post('/advertisements/{advertisement}/bid', [\App\Http\Controllers\BidController::class, 'store'])->name('bids.store');
    Route::post('/advertisements/{advertisement}/rent', [\App\Http\Controllers\RentalController::class, 'store'])->name('rentals.store');
    Route::post('/rentals/{rental}/return', [\App\Http\Controllers\RentalReturnController::class, 'store'])->name('rentals.return');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
