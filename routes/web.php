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
Route::get('/company/{company:custom_url_slug}', [CompanyController::class, 'show'])->name('company.show');

// ZONE B: Dashboard (Secure)
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', function () {
        $user = auth()->user();
        return view('pages.dashboard.index', [
            // "My Activity" - things I've bought/rented
            'myRentals' => $user->rentals()->with('advertisement')->latest()->get(),
            'myBids'    => $user->bids()->with('advertisement')->latest()->get(),
            'myAds'     => $user->advertisements()->latest()->take(5)->get(),
            
            // "My Sales" - things people bought/rented FROM me (if I'm an advertiser)
            'incomingRentals' => \App\Models\Rental::whereHas('advertisement', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['advertisement', 'renter'])->get(),
        ]);
    })->name('index');

    Route::get('/rentals', [\App\Http\Controllers\RentalController::class, 'index'])->name('rentals.index');
    Route::get('/bids', [\App\Http\Controllers\BidController::class, 'index'])->name('bids.index');
    Route::resource('advertisements', AdvertisementController::class);
    
    // Company Settings
    Route::get('/company/settings', [App\Http\Controllers\Dashboard\CompanySettingsController::class, 'edit'])->name('company.settings.edit');
    Route::patch('/company/settings', [App\Http\Controllers\Dashboard\CompanySettingsController::class, 'update'])->name('company.settings.update');

    // Page Components
    Route::post('/company/components', [App\Http\Controllers\Dashboard\PageComponentController::class, 'store'])->name('company.components.store');
    Route::patch('/company/components/{component}', [App\Http\Controllers\Dashboard\PageComponentController::class, 'update'])->name('company.components.update');
    Route::delete('/company/components/{component}', [App\Http\Controllers\Dashboard\PageComponentController::class, 'destroy'])->name('company.components.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/advertisements/{advertisement}/bid', [\App\Http\Controllers\BidController::class, 'store'])->name('bids.store');
    Route::delete('/bids/{bid}', [\App\Http\Controllers\BidController::class, 'destroy'])->name('bids.destroy');
    Route::post('/advertisements/{advertisement}/rent', [\App\Http\Controllers\RentalController::class, 'store'])->name('rentals.store');
    Route::post('/rentals/{rental}/return', [\App\Http\Controllers\RentalReturnController::class, 'store'])->name('rentals.return');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
