<?php

use App\Http\Controllers\Api\CompanyAdController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Protected API routes for business users with approved contracts
Route::middleware(['auth:sanctum', 'contract.approved'])->group(function () {
    Route::get('/my-ads', [CompanyAdController::class, 'index']);
});
