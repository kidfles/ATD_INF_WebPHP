<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Rental;
use App\Services\WearAndTearCalculator;
use Illuminate\Http\RedirectResponse;

class RentalReturnController extends Controller
{
    public function store(Request $request, Rental $rental, WearAndTearCalculator $calculator): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:5000'] // 5MB max
        ]);

        // 1. Handle Photo Upload
        $path = $request->file('photo')->store('returns', 'public');

        // 2. Calculate Costs via Service
        $finalCost = $calculator->calculate($rental);

        // 3. Update Database
        $rental->update([
            'return_photo_path' => $path,
            'wear_and_tear_cost' => $finalCost,
            // 'status' => 'returned' 
        ]);

        return redirect()->back()->with('status', "Product ingeleverd. Totale kosten: €" . number_format($finalCost, 2));
    }
}
