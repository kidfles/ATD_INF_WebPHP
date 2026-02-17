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
            PageComponent::create([
                'company_id' => $profile->id,
                'component_type' => 'hero',
                'order' => 1,
                'content' => json_encode([
                    'title' => 'Welcome to ' . $profile->company_name,
                    'subtitle' => 'The best place for your needs.',
                    'image' => 'images/placeholders/hero.jpg',
                ]),
            ]);

            // 2. Text Component
            PageComponent::create([
                'company_id' => $profile->id,
                'component_type' => 'text',
                'order' => 2,
                'content' => json_encode([
                    'body' => 'We are dedicated to providing top quality service and products. Check out our latest offerings below.',
                ]),
            ]);

            // 3. Featured Ads Component
            PageComponent::create([
                'company_id' => $profile->id,
                'component_type' => 'featured_ads',
                'order' => 3,
                'content' => json_encode([
                    'limit' => 3,
                    'title' => 'Our Top Picks',
                ]),
            ]);
        }
    }
}
