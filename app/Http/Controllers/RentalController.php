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
    public function index()
    {
        $rentals = auth()->user()->rentals()->with('advertisement')->latest()->get();
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
        // Toekomstige logica: Controleer beschikbaarheid van het item voor de gekozen data
        
        $advertisement->rentals()->create([
            'renter_id' => $request->user()->id,
            'start_date' => $request->validated('start_date'),
            'end_date' => $request->validated('end_date'),
        ]);

        return back()->with('status', 'Verhuur succesvol geboekt!');
    }
}
