<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\StoreRentalRequest;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
    public function index(Request $request): View
    {
        $view = $request->get('view', 'rented'); // 'rented' of 'rented_out'
        $user = auth()->user();

        if ($view === 'rented_out') {
            // Items die door anderen van mij worden gehuurd
            $query = \App\Models\Rental::whereHas('advertisement', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } else {
            // Items die ikzelf huur
            $query = $user->rentals();
        }

        $rentals = $query->filter($request->only(['search', 'status', 'sort']))
            ->with(['advertisement.user', 'renter']) // Fix N+1 voor beide scenario's
            ->paginate(10)
            ->withQueryString();

        return view('pages.dashboard.rentals.index', compact('rentals', 'view'));
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
        // Prevent renting own item
        if ($advertisement->user_id === auth()->id()) {
            return back()->withErrors(['start_date' => __('You cannot rent your own advertisement.')])->withInput();
        }

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

        $start = \Carbon\Carbon::parse($request->validated('start_date'));
        $end = \Carbon\Carbon::parse($request->validated('end_date'));
        
        // Use the newly fixed logic: minimum of 1 day
        $days = max(1, $start->diffInDays($end));
        $totalPrice = $days * $advertisement->price;

        $advertisement->rentals()->create([
            'renter_id' => $request->user()->id,
            'start_date' => $start,
            'end_date' => $end,
            'total_price' => $totalPrice,
        ]);

        return back()->with('success', __('Rental successfully booked!'));
    }
}
