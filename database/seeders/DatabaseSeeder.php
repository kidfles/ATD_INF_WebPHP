<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Advertisement;
use App\Models\CompanyProfile;
use App\Models\PageComponent;
use App\Models\Rental;
use App\Models\Bid;
use App\Models\Review;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // 2. Business Advertisers (10)
        $businessUsers = User::factory(10)->businessAdvertiser()->create();

        // Specific Test Business for Whitelabel
        $testBusiness = User::factory()->businessAdvertiser()->create([
            'name' => 'Test Business',
            'email' => 'business@example.com',
        ]);
        
        // Create Company Profile for Test Business
        $testCompany = CompanyProfile::factory()->create([
            'user_id' => $testBusiness->id,
            // 'company_name' removed as it doesn't exist in the model/migration
            'custom_url_slug' => 'test-company',
            'brand_color' => '#e11d48', // distinct red/pink
            'kvk_number' => '12345678',
        ]);

        PageComponent::factory()->create([
            'company_id' => $testCompany->id,
            'component_type' => 'hero',
            'order' => 1,
            'content' => [
                'title' => 'Welcome to Test Company',
                'subtitle' => 'We provide the best services for testing purposes.',
                'image' => null, // or a placeholder
            ],
        ]);

        PageComponent::factory()->create([
            'company_id' => $testCompany->id,
            'component_type' => 'text',
            'order' => 2,
            'content' => [
                'heading' => 'About Us',
                'body' => 'This is a test company created by the seeder to demonstrate the whitelabel capabilities.',
            ],
        ]);

         PageComponent::factory()->create([
            'company_id' => $testCompany->id,
            'component_type' => 'featured_ads',
            'order' => 3,
            'content' => [],
        ]);

        Advertisement::factory(5)->create([
            'user_id' => $testBusiness->id,
            'type' => 'sell',
            'title' => 'Test Product',
            'price' => 99.99,
        ]);


        // For each business user, create a Company Profile and Landing Page Components
        foreach ($businessUsers as $user) {
            $company = CompanyProfile::factory()->create([
                'user_id' => $user->id,
            ]);

            PageComponent::factory(3)->create([
                'company_id' => $company->id,
            ]);
            
            // Create advertisements for this business
            Advertisement::factory(5)->create([
                'user_id' => $user->id,
                'type' => 'sell', // Businesses mostly sell
            ]);
        }

        // 3. Private Advertisers (10)
        $privateAdvertisers = User::factory(10)->privateAdvertiser()->create();
        
        foreach ($privateAdvertisers as $user) {
            // Create mostly sell/rent ads
            Advertisement::factory(3)->create([
                'user_id' => $user->id,
            ]);
        }
        
        // 4. Regular Users (30) - Potential buyers/renters
        $users = User::factory(30)->create();
        
        // 5. Interactions (Rentals, Bids, Reviews, Favorites)
        $allAds = Advertisement::all();
        
        foreach ($allAds as $ad) {
            // Create bids for auction ads
            if ($ad->type === 'auction') {
                Bid::factory(rand(0, 5))->create([
                    'advertisement_id' => $ad->id,
                    'user_id' => $users->random()->id,
                ]);
            }
            
            // Create rentals for rent ads
            if ($ad->type === 'rent') {
                Rental::factory(rand(0, 3))->create([
                    'advertisement_id' => $ad->id,
                    'renter_id' => $users->random()->id,
                ]);
            }
            
            // Random reviews
            if (rand(0, 1)) {
                 Review::factory()->create([
                    'reviewer_id' => $users->random()->id,
                    'reviewable_id' => $ad->id,
                    'reviewable_type' => Advertisement::class,
                 ]);
            }

            // Favorites
            $users->random(rand(0, 3))->each(function ($user) use ($ad) {
                $user->favorites()->attach($ad->id);
            });
        }
    }
}
