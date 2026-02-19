<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Dashboard\ContractController;
use App\Http\Controllers\AdvertisementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// Taalwissel route
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'nl'])) {
        Session::put('locale', $locale);
    }
    return back();
})->name('lang.switch');

// ZONE A: Openbare Marktplaats
// Deze routes zijn voor iedereen toegankelijk (bezoekers en ingelogde gebruikers).

Route::get('/', function () {
    $featuredAds = \App\Models\Advertisement::latest()->take(6)->get();
    return view('pages.home', compact('featuredAds'));
})->name('home');

Route::get('/market', [MarketController::class, 'index'])->name('market.index');
Route::get('/market/{advertisement}', [MarketController::class, 'show'])->name('market.show');

// ZONE C: Whitelabel Bedrijfspagina's
// Dynamische routes op basis van de custom slug van een bedrijf.
Route::get('/company/{company:custom_url_slug}', [CompanyController::class, 'show'])->name('company.show');

// Openbaar Verkopersprofiel (Universeel voor particulieren en bedrijven)
Route::get('/verkoper/{user}', [\App\Http\Controllers\SellerProfileController::class, 'show'])->name('seller.show');

// Reviews voor verkopers (POST)
Route::post('/verkoper/{user}/reviews', [\App\Http\Controllers\ReviewController::class, 'storeSeller'])->name('reviews.storeSeller')->middleware('auth');

// ZONE B: Dashboard (Beveiligd)
// Alleen toegankelijk voor ingelogde en geverifieerde gebruikers.
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    
    // Dashboard Startpagina (Overzicht van eigen activiteit en verkoop)
    Route::get('/', function () {
        $user = auth()->user();
        return view('pages.dashboard.index', [
            // "Mijn Activiteit" - zaken die ik heb gekocht/gehuurd
            'myRentals' => $user->rentals()->with('advertisement')->latest()->get(),
            'myBids'    => $user->bids()->with('advertisement')->latest()->take(5)->get(),
            'myBidsCount' => $user->bids()->count(),
            'myAds'     => $user->advertisements()->latest()->take(5)->get(),
            
            // "Mijn Verkoop" - zaken die anderen bij mij hebben gekocht/gehuurd
            'incomingRentals' => \App\Models\Rental::whereHas('advertisement', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['advertisement', 'renter'])->get(),
        ]);
    })->name('index');

    // Beheer van eigen verhuur, biedingen en advertenties
    Route::get('/rentals', [\App\Http\Controllers\RentalController::class, 'index'])->name('rentals.index');
    Route::get('/bids', [\App\Http\Controllers\BidController::class, 'index'])->name('bids.index');
    Route::resource('advertisements', AdvertisementController::class);

    // Bestellingen en Favorieten (Verplaatst voor verified check)
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    
    // Bedrijfsinstellingen (Alleen voor zakelijke adverteerders)
    Route::get('/company/settings', [App\Http\Controllers\Dashboard\CompanySettingsController::class, 'edit'])->name('company.settings.edit');
    Route::patch('/company/settings', [App\Http\Controllers\Dashboard\CompanySettingsController::class, 'update'])->name('company.settings.update');

    // Pagina Componenten (Whitelabel builder)
    Route::post('/company/components', [App\Http\Controllers\Dashboard\PageComponentController::class, 'store'])->name('company.components.store');
    Route::post('/company/components/bulk', [App\Http\Controllers\Dashboard\PageComponentController::class, 'bulkUpdate'])->name('company.components.bulk');
    Route::delete('/company/components/{component}', [App\Http\Controllers\Dashboard\PageComponentController::class, 'destroy'])->name('company.components.destroy');
    
    // Contractbeheer en Goedkeuring
    Route::get('/company/contract/download', [ContractController::class, 'download'])->name('company.contract.download');
    Route::post('/company/contract/upload', [ContractController::class, 'upload'])->name('company.contract.upload');
    Route::post('/company/contract/approve-test', [ContractController::class, 'approveTest'])->name('company.contract.approve_test');
    
    // API Toegang (Alleen als contract is goedgekeurd)
    Route::post('/company/api-token', [App\Http\Controllers\Dashboard\CompanySettingsController::class, 'generateToken'])->name('company.api_token')->middleware('contract.approved');
    
    // CSV Bulk Import (Advertenties inladen via bestand)
    Route::post('/company/import-csv', [App\Http\Controllers\Dashboard\CompanySettingsController::class, 'importCsv'])->name('company.import_csv');

    // Agenda (Visueel overzicht van verhuur en verloopdatums)
    Route::get('/agenda', [App\Http\Controllers\Dashboard\AgendaController::class, 'index'])->name('agenda.index');
    Route::get('/agenda/events', [App\Http\Controllers\Dashboard\AgendaController::class, 'events'])->name('agenda.events');
});

// Algemene Beveiligde Routes (Auth middleware)
Route::middleware('auth')->group(function () {
    
    // Acties op advertenties (Bieden, Huren, Kopen)
    Route::post('/advertisements/{advertisement}/bid', [\App\Http\Controllers\BidController::class, 'store'])->name('bids.store');
    Route::delete('/bids/{bid}', [\App\Http\Controllers\BidController::class, 'destroy'])->name('bids.destroy');
    Route::post('/advertisements/{advertisement}/rent', [\App\Http\Controllers\RentalController::class, 'store'])->name('rentals.store');
    Route::post('/advertisements/{advertisement}/buy', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::post('/rentals/{rental}/return', [\App\Http\Controllers\RentalReturnController::class, 'store'])->name('rentals.return');
    
    // Gebruikersprofiel Beheer
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Favorieten
    Route::post('/advertisements/{advertisement}/favorite', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
    
    // Reviews plaatsen op advertenties
    Route::post('/advertisements/{advertisement}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

// Authenticatie Routes (Breeze/Fortify)
require __DIR__.'/auth.php';
