<?php declare(strict_types=1);

namespace App\Observers;

use App\Models\CompanyProfile;

class CompanyProfileObserver
{
    /**
     * Het CompanyProfile "creating" event.
     */
    public function creating(CompanyProfile $companyProfile): void
    {
        if (!$companyProfile->custom_url_slug) {
            $slug = \Illuminate\Support\Str::slug($companyProfile->company_name);
            $uniqueSlug = $slug;
            $counter = 1;

            // Controleer op uniciteit en voeg een teller toe indien nodig
            while (CompanyProfile::where('custom_url_slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $slug . '-' . $counter++;
            }

            $companyProfile->custom_url_slug = $uniqueSlug;
        }
    }

    /**
     * Het CompanyProfile "created" event.
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
