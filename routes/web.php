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

    Route::resource('advertisements', AdvertisementController::class);
    
    // Future: Rentals, Favorites, etc.
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
