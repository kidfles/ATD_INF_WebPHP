<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Rental;
use App\Services\WearAndTearCalculator;
use Illuminate\Http\RedirectResponse;

/**
 * RentalReturnController
 * 
 * Beheert de inlevering van gehuurde items.
 * Verwerkt foto-uploads voor kwaliteitscontrole en berekent eventuele slijtagekosten.
 */
class RentalReturnController extends Controller
{
    /**
     * Sla de inlevering van een gehuurd product op.
     * 
     * @param Request $request Het request met de bewijsfoto van inlevering.
     * @param Rental $rental Het verhuurrecord dat wordt afgerond.
     * @param WearAndTearCalculator $calculator Service voor het berekenen van slijtagekosten.
     * @return RedirectResponse Redirect terug met status- en kostenoverzicht.
     */
    public function store(Request $request, Rental $rental, WearAndTearCalculator $calculator): RedirectResponse
    {
        // 0. Security: Ensure user is authorized (Strict: Only renter can submit return proof)
        abort_unless(
            $rental->renter_id === auth()->id(),
            403
        );

        $request->validate([
            'photo' => ['required', 'image', 'max:5000'] // Maximaal 5MB
        ]);

        // 1. Afbeelding van inlevering uploaden naar de public disk
        $path = $request->file('photo')->store('returns', 'public');

        // 2. Kosten berekenen via de WearAndTearCalculator Service
        $result = $calculator->calculate($rental);
        $finalCost = $result['total'];
        $breakdown = $result['breakdown'];

        // 3. Database record bijwerken met de resultaten
        $rental->update([
            'return_photo_path' => $path,
            'wear_and_tear_cost' => $finalCost,
            // Optioneel: status bijwerken naar 'returned'
        ]);

        // 4. Feedback geven aan de gebruiker
        $message = __('rental.returned_cost_breakdown', [
            'total' => number_format($finalCost, 2),
            'base' => number_format($breakdown['base_cost'], 2),
            'late_fee' => number_format($breakdown['late_fee'], 2),
            'wear_and_tear' => number_format($breakdown['wear_and_tear'], 2),
        ]);

        return redirect()->back()->with('status', $message);
    }
}
