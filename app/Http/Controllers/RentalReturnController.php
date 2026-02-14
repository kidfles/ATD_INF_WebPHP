<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Rental;
use App\Services\WearAndTearCalculator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RentalReturnController extends Controller
{
    public function store(Request $request, Rental $rental, WearAndTearCalculator $calculator): RedirectResponse
    {
        $request->validate(['photo' => 'required|image']);

        // 1. Handle Photo Upload
        $path = $request->file('photo')->store('returns', 'public');

        // 2. Calculate Final Cost using Service
        $finalCost = $calculator->calculate($rental);

        // 3. Update Database
        $rental->update([
            'return_photo_path' => $path,
            'wear_and_tear_cost' => $finalCost,
            // 'status' => 'returned' // if you add a status column
        ]);

        return redirect()->back()->with('status', "Item returned. Total cost: €{$finalCost}");
    }
}
