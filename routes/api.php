<?php

use App\Http\Controllers\Api\CompanyAdController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route voor het ophalen van de huidige ingelogde gebruiker via Sanctum
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Beveiligde API routes voor zakelijke gebruikers met een goedgekeurd contract
Route::middleware(['auth:sanctum', 'contract.approved'])->group(function () {
    
    // Haal de eigen advertenties van het bedrijf op
    Route::get('/my-ads', [CompanyAdController::class, 'index']);
});
