<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        // 1. Handle "Clear Filters"
        if ($request->has('clear')) {
            session()->forget('ad_filters');
            return redirect()->route('market.index');
        }

        // 2. "Sticky" Logic
        if ($request->hasAny(['search', 'type', 'sort'])) {
            session(['ad_filters' => $request->only(['search', 'type', 'sort'])]);
        } elseif (session()->has('ad_filters')) {
            return redirect()->route('market.index', session('ad_filters'));
        }

        // 3. Default Sort (Newest First)
        if (!$request->has('sort') && !session()->has('ad_filters.sort')) {
            $request->merge(['sort' => 'newest']);
        }

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
