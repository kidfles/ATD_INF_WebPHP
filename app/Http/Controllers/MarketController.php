<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        // Public Market Logic: Show all ads, filterable
        $advertisements = Advertisement::filter($request->only(['search', 'type', 'sort']))
            ->with('user')
            ->paginate(12)
            ->withQueryString();

        return view('pages.market.index', compact('advertisements'));
    }

    public function show(Advertisement $advertisement)
    {
        $advertisement->load(['user', 'bids.user']); // Eager load for view
        return view('pages.market.show', compact('advertisement'));
    }
}
