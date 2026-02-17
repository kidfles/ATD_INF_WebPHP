<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\PageComponent;
use Illuminate\Database\Seeder;

class PageComponentSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = CompanyProfile::all();

        foreach ($profiles as $profile) {
            // 1. Hero Component
            // Doel: De visuele binnenkomer van de bedrijfspagina.
            // Bevat: Titel, ondertitel en een grote achtergrondafbeelding.
            PageComponent::create([
                'company_id' => $profile->id,
                'component_type' => 'hero',
                'order' => 1,
                'content' => [
                    'title' => 'Welkom bij ' . $profile->company_name,
                    'subtitle' => 'De beste plek voor al uw behoeften.',
                    'image' => 'images/placeholders/hero.jpg',
                ],
            ]);

            // 2. Text Component
            // Doel: Informatief blok om het bedrijf voor te stellen.
            PageComponent::create([
                'company_id' => $profile->id,
                'component_type' => 'text',
                'order' => 2,
                'content' => [
                    'body' => 'Wij zijn toegewijd aan het leveren van topkwaliteit service en producten. Bekijk ons aanbod hieronder.',
                ],
            ]);

            // 3. Featured Ads Component
            // Doel: Automatisch de populairste of nieuwste advertenties tonen.
            // Configuratie: 'limit' bepaalt hoeveel items er worden getoond.
            PageComponent::create([
                'company_id' => $profile->id,
                'component_type' => 'featured_ads',
                'order' => 3,
                'content' => [
                    'limit' => 3,
                    'title' => 'Onze Toppers',
                ],
            ]);
        }
    }
}
