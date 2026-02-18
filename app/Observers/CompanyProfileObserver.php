<?php

namespace App\Observers;

use App\Models\CompanyProfile;

class CompanyProfileObserver
{
    /**
     * Handle the CompanyProfile "created" event.
     */
    public function created(CompanyProfile $companyProfile): void
    {
        // Default 1: Hero Sectie (Grote titel)
        $companyProfile->pageComponents()->create([
            'component_type' => 'hero',
            'order' => 1,
            'content' => [
                'title' => $companyProfile->company_name,
                'subtitle' => 'Welkom op onze bedrijfspagina',
                'image_url' => null
            ]
        ]);

        // Default 2: Tekst Sectie
        $companyProfile->pageComponents()->create([
            'component_type' => 'text',
            'order' => 2,
            'content' => [
                'heading' => 'Over Ons',
                'body' => 'Wij zijn gespecialiseerd in het aanbieden van kwaliteitsproducten en diensten. Neem gerust contact met ons op voor meer informatie.'
            ]
        ]);

        // Default 3: Uitgelichte Advertenties
        $companyProfile->pageComponents()->create([
            'component_type' => 'featured_ads',
            'order' => 3,
            'content' => [] // Logica pakt automatisch de nieuwste advertenties
        ]);
    }
}
