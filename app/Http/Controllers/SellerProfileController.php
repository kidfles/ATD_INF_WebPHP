<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * SellerProfileController
 * 
 * Beheert de weergave van verkopersprofielen voor zowel particulieren als bedrijven.
 * Combineert advertenties, reviews en eventuele whitelabel-componenten.
 */
class SellerProfileController extends Controller
{
    /**
     * Toon het profiel van een specifieke verkoper.
     * Laadt de benodigde relaties en berekent de gemiddelde score.
     * 
     * @param User $user De gebruiker (verkoper) wiens profiel getoond wordt.
     * @return \Illuminate\View\View De weergave van het verkopersprofiel.
     */
    public function show(User $user)
    {
        // 1. Benodigde relaties inladen voor de weergave
        $user->load([
            'advertisements' => function($q) {
                $q->latest()->limit(10); // Beperk het aantal advertenties voor prestaties
            }, 
            'reviewsReceived.reviewer', // Polymorfe relatie voor verkregen reviews
            'companyProfile.pageComponents' // Laad bedrijfscomponenten als het een zakelijke adverteerder is
        ]);

        // 2. Bepaal het type profiel voor de juiste UI-rendering
        $isBusiness = $user->isBusinessAdvertiser(); 
        
        // Voor zakelijke adverteerders proberen we de Hero component te vinden
        $heroComponent = ($isBusiness && $user->companyProfile)
            ? $user->companyProfile->pageComponents->where('component_type', 'hero')->first() 
            : null;

        // 3. Statistieken berekenen voor de weergave
        $averageRating = $user->reviewsReceived->avg('rating') ?? 0;
        $reviewCount = $user->reviewsReceived->count();

        return view('pages.seller.profile', compact(
            'user', 
            'isBusiness', 
            'heroComponent',
            'averageRating',
            'reviewCount'
        ));
    }
}
