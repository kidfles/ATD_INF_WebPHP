<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use App\Enums\AdvertisementType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * OrderController
 * 
 * Beheert de bestellingen en aankoophistorie van de gebruiker.
 * Verwerkt de transacties wanneer een product wordt gekocht.
 */
class OrderController extends Controller
{
    /**
     * Sla een nieuwe bestelling op in de database.
     * Verwerkt de betaling (simulatie) en markeert de advertentie als verkocht.
     * 
     * @param Request $request Het huidige HTTP request.
     * @param Advertisement $advertisement De advertentie die gekocht wordt.
     * @return \Illuminate\Http\RedirectResponse Redirect naar het besteloverzicht.
     */
    public function store(Request $request, Advertisement $advertisement): RedirectResponse
    {
        $user = Auth::user();

        // 1. Validatie: Je mag je eigen product niet kopen
        if ($advertisement->user_id === $user->id) {
            return back()->with('error', __('You cannot buy your own product.'));
        }

        // 2. Validatie: Is het product al verkocht?
        if ($advertisement->is_sold) {
            return back()->with('error', __('Sadly, this product is already sold.'));
        }

        // 3. Validatie: Is het een verkoop advertentie? (Geen huur of veiling)
        if ($advertisement->type !== AdvertisementType::Sale) {
            return back()->with('error', __('This type of advertisement cannot be bought directly.'));
        }

        // 4. Maak de Order aan binnen een Database Transactie voor data-integriteit
        DB::transaction(function () use ($user, $advertisement) {
            Order::create([
                'buyer_id' => $user->id,
                'seller_id' => $advertisement->user_id,
                'advertisement_id' => $advertisement->id,
                'amount' => $advertisement->price,
                'status' => 'completed',
            ]);

            // 5. Werk de advertentiestatus bij naar verkocht
            $advertisement->update([
                'is_sold' => true
            ]);
        });

        // 6. Feedback en redirect naar de bestelhistorie
        return redirect()->route('dashboard.orders.index')
            ->with('success', __('Congratulations! You bought the product.'));
    }
    
    /**
     * Toon een lijst met de aankoophistorie van de ingelogde gebruiker.
     * 
     * @return \Illuminate\View\View De weergave met alle geplaatste bestellingen.
     */
    public function index(Request $request): View
    {
        $orders = Auth::user()->orders()
            ->filter($request->only(['search', 'sort']))
            ->with(['advertisement.user', 'seller'])
            ->paginate(10)
            ->withQueryString();

        return view('pages.dashboard.orders.index', compact('orders'));
    }
}
