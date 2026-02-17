<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\StoreRentalRequest;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;

/**
 * RentalController
 * 
 * Beheert de verhuur-transacties voor advertenties van het type 'rent'.
 * Maakt het mogelijk voor gebruikers om items te huren voor een specifieke periode.
 */
class RentalController extends Controller
{
    /**
     * Toon een overzicht van alle actieve en eerdere verhuringen van de gebruiker.
     * 
     * @return \Illuminate\View\View De weergave met de verhuurhistorie.
     */
    public function index(Request $request)
    {
        $rentals = auth()->user()->rentals()
            ->filter($request->only(['search', 'status', 'sort']))
            ->with('advertisement')
            ->paginate(10)
            ->withQueryString();

        return view('pages.dashboard.rentals.index', compact('rentals'));
    }

    /**
     * Sla een nieuwe verhuuraanvraag op in de database.
     * 
     * @param StoreRentalRequest $request Het gevalideerde request met huurdata.
     * @param Advertisement $advertisement De advertentie die gehuurd wordt.
     * @return RedirectResponse Redirect terug met statusmelding.
     */
    public function store(StoreRentalRequest $request, Advertisement $advertisement): RedirectResponse
    {
        // Check availability
        $start = $request->validated('start_date');
        $end = $request->validated('end_date');

        $exists = $advertisement->rentals()
            ->where(function ($q) use ($start, $end) {
                $q->where('start_date', '<=', $end)
                  ->where('end_date', '>=', $start);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['start_date' => __('This item is already rented for the selected dates.')])->withInput();
        }

        $advertisement->rentals()->create([
            'renter_id' => $request->user()->id,
            'start_date' => $start,
            'end_date' => $end,
        ]);

        return back()->with('success', __('Rental successfully booked!'));
    }
}
