<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request, Advertisement $advertisement)
    {
        $user = Auth::user();

        // 1. Validatie: Je mag je eigen product niet kopen
        if ($advertisement->user_id === $user->id) {
            return back()->with('error', 'Je kunt je eigen product niet kopen.');
        }

        // 2. Validatie: Is het product al verkocht?
        if ($advertisement->is_sold) {
            return back()->with('error', 'Dit product is helaas al verkocht.');
        }

        // 3. Maak de Order aan (Transactie)
        Order::create([
            'buyer_id' => $user->id,
            'seller_id' => $advertisement->user_id,
            'advertisement_id' => $advertisement->id,
            'amount' => $advertisement->price,
            'status' => 'completed', // In een echte app zou hier de betaalstatus komen
        ]);

        // 4. Update de advertentie status
        $advertisement->update([
            'is_sold' => true
        ]);

        // 5. Feedback en redirect (bijv. naar de order historie)
        return redirect()->route('dashboard.orders.index')
            ->with('success', 'Gefeliciteerd! Je hebt het product gekocht.');
    }
    
    // Voor de User Story: "Als gebruiker wil ik een historie kunnen zien van gekochte producten"
    public function index()
    {
        $orders = Auth::user()->orders()->with('advertisement.user')->latest()->get();
        return view('pages.dashboard.orders.index', compact('orders'));
    }
}
