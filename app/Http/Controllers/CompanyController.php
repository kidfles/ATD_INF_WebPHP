<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\PageComponent;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function show(CompanyProfile $company)
    {
        // 1. Check: Heeft dit bedrijf nog geen componenten? Maak dan defaults aan.
        if ($company->pageComponents()->count() === 0) {
            


            // Default 2: Tekst Sectie
            $company->pageComponents()->create([
                'component_type' => 'text',
                'order' => 2,
                'content' => [
                    'heading' => 'Over Ons',
                    'body' => 'Wij zijn gespecialiseerd in het aanbieden van kwaliteitsproducten en diensten. Neem gerust contact met ons op voor meer informatie.'
                ]
            ]);

            // Default 3: Uitgelichte Advertenties
            $company->pageComponents()->create([
                'component_type' => 'featured_ads',
                'order' => 3,
                'content' => [] // Logica pakt automatisch de nieuwste ads
            ]);
            
            // Ververs de relatie zodat de nieuwe componenten direct geladen worden
            $company->refresh();
        }

        // 2. Laad de data (nu gegarandeerd aanwezig)
        // 2. Laad de data (nu gegarandeerd aanwezig)
        // We laden ook de reviews van de USER die bij dit bedrijf hoort.
        $company->load(['user.advertisements' => function ($query) {
            $query->latest()->limit(3); // Fix N+1 and limit ads
        }, 'user.reviewsReceived.reviewer', 'pageComponents' => function ($query) {
            $query->orderBy('order');
        }]);

        // Bereken stats (op basis van de User, want daar hangen de reviews aan)
        $user = $company->user;
        $averageRating = $user->reviewsReceived()->avg('rating') ?? 0;
        $reviewCount = $user->reviewsReceived()->count();
        
        return view('pages.whitelabel.show', compact('company', 'averageRating', 'reviewCount', 'user'));
    }
}
