<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

/**
 * MarketController
 * 
 * Beheert de openbare marktplaats waar alle advertenties getoond worden.
 * Ondersteunt geavanceerde filtering, sortering en "sticky" zoekopdrachten via de sessie.
 */
class MarketController extends Controller
{
    /**
     * Toon de marktplaats met een overzicht van alle advertenties.
     * Implementeert "sticky" filterlogica om zoekopdrachten te onthouden.
     * 
     * @param Request $request Het huidige HTTP request met filters.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse De marktplaats weergave of een redirect.
     */
    public function index(Request $request)
    {
        // 1. Afhandelen van "Filters Wissen"
        if ($request->has('clear')) {
            session()->forget('ad_filters');
            return redirect()->route('market.index');
        }

        // 2. "Sticky" Logica: Bewaar filters in de sessie voor een betere UX
        if ($request->hasAny(['search', 'type', 'sort'])) {
            session(['ad_filters' => $request->only(['search', 'type', 'sort'])]);
        } elseif (session()->has('ad_filters')) {
            // Als er geen nieuwe filters zijn maar wel in sessie staan, redirect naar opgeslagen filters
            return redirect()->route('market.index', session('ad_filters'));
        }

        // 3. Standaard Sortering (Nieuwste eerst)
        if (!$request->has('sort') && !session()->has('ad_filters.sort')) {
            $request->merge(['sort' => 'newest']);
        }

        // Publieke Markt Logica: Toon alle advertenties, gefilterd
        $advertisements = Advertisement::filter($request->only(['search', 'type', 'sort', 'seller']))
            ->with('user')
            ->paginate(12)
            ->withQueryString();

        return view('pages.market.index', compact('advertisements'));
    }

    /**
     * Toon een specifieke advertentie op de openbare marktplaats.
     * 
     * @param Advertisement $advertisement Het advertentiemodel.
     * @return \Illuminate\View\View De detailweergave van de advertentie.
     */
    public function show(Advertisement $advertisement)
    {
        // Eager load het bedrijfsprofiel van de verkoper voor branding
        $advertisement->load(['user.companyProfile', 'bids.user', 'relatedAds']);
        
        // Bepaal de merkkleur (Standaard naar Indigo als er geen bedrijfsprofiel is)
        $brandColor = $advertisement->user->companyProfile->brand_color ?? '#4f46e5'; 

        // Haal toekomstige verhuringen op voor de beschikbaarheidskalender
        $rentals = $advertisement->rentals()
            ->where('end_date', '>=', now()->startOfDay())
            ->get(['start_date', 'end_date']);

        return view('pages.market.show', compact('advertisement', 'brandColor', 'rentals'));
    }
}
