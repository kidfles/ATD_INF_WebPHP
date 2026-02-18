<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;

/**
 * FavoriteController
 * 
 * Beheert de favoriete advertenties van de ingelogde gebruiker.
 * Maakt het mogelijk om advertenties op te slaan in een persoonlijke lijst.
 */
class FavoriteController extends Controller
{
    /**
     * Schakel een advertentie 'aan' of 'uit' in de favorietenlijst van de gebruiker.
     * Voegt de advertentie toe als deze nog niet bestaat, anders wordt deze verwijderd.
     * 
     * @param Advertisement $advertisement De advertentie die getoggled moet worden.
     * @return \Illuminate\Http\RedirectResponse Redirect terug naar de vorige pagina.
     */
    public function toggle(Advertisement $advertisement)
    {
        // Toggle voegt toe als het niet bestaat, verwijdert als het wel bestaat (pivot tabel)
        auth()->user()->favorites()->toggle($advertisement->id);

        return back();
    }
    
    /**
     * Toon een overzicht van alle favoriete advertenties van de gebruiker.
     * 
     * @return \Illuminate\View\View De weergave met de favorietenlijst.
     */
    public function index()
    {
        $favorites = auth()->user()->favorites()
            ->filter(request()->only(['search', 'sort', 'type']))
            ->with('user')
            ->paginate(12)
            ->withQueryString();
            
        return view('pages.dashboard.favorites.index', compact('favorites'));
    }
}
