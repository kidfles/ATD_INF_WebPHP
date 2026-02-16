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
        // ==========================================
        // 1. SYSTEM USERS (Specific Accounts)
        // ==========================================
        
        // Admin
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Business User (Primary)
        $techBusiness = User::factory()->businessAdvertiser()->create([
            'name' => 'Tech Solutions BV',
            'email' => 'business@example.com',
            'password' => bcrypt('password'),
        ]);

        // Private User (Primary)
        $privateUser = User::factory()->privateAdvertiser()->create([
            'name' => 'John Doe',
            'email' => 'private@example.com',
            'password' => bcrypt('password'),
        ]);

        // Standard User (Buyer/Renter)
        $regularUser = User::factory()->create([
            'name' => 'Jane Buyer',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        // ==========================================
        // 2. COMPANY PROFILES & PAGES
        // ==========================================

        // -- Tech Solutions BV (Electronics & Gadgets) --
        $techCompany = CompanyProfile::factory()->create([
            'user_id' => $techBusiness->id,
            'company_name' => 'Tech Solutions BV',
            'custom_url_slug' => 'tech-solutions',
            'brand_color' => '#3b82f6', // Blue
            'kvk_number' => '90000001',
            'contract_status' => 'approved',
        ]);

        // Hero Component
        PageComponent::factory()->create([
            'company_id' => $techCompany->id,
            'component_type' => 'hero',
            'order' => 1,
            'content' => [
                'title' => 'Future Tech Today',
                'subtitle' => 'Premium electronics for professionals.',
                'image' => null,
            ],
        ]);

        // Featured Ads Component
        PageComponent::factory()->create([
            'company_id' => $techCompany->id,
            'component_type' => 'featured_ads',
            'order' => 2,
            'content' => [],
        ]);

        // Text Component
        PageComponent::factory()->create([
            'company_id' => $techCompany->id,
            'component_type' => 'text',
            'order' => 3,
            'content' => [
                'heading' => 'About Tech Solutions',
                'body' => 'We are the leading provider of refurbished and high-end electronics in the region. Trusted by over 500 businesses.',
            ],
        ]);

        // -- Rent-A-Tool (Construction Rental) --
        $toolBusiness = User::factory()->businessAdvertiser()->create([
            'name' => 'Rent-A-Tool',
            'email' => 'tools@example.com',
            'password' => bcrypt('password'),
        ]);

        $toolCompany = CompanyProfile::factory()->create([
            'user_id' => $toolBusiness->id,
            'company_name' => 'Rent-A-Tool',
            'custom_url_slug' => 'rent-a-tool',
            'brand_color' => '#f59e0b', // Orange
            'kvk_number' => '90000002',
            'contract_status' => 'approved',
        ]);

        PageComponent::factory()->create([
            'company_id' => $toolCompany->id,
            'component_type' => 'hero',
            'order' => 1,
            'content' => [
                'title' => 'Build It Better',
                'subtitle' => 'Professional tools for every job.',
            ],
        ]);
        
        PageComponent::factory()->create([
            'company_id' => $toolCompany->id,
            'component_type' => 'advertisement_grid',
            'order' => 2,
            'content' => [],
        ]);

        // ==========================================
        // 3. ADVERTISEMENTS (Realistic Mix)
        // ==========================================

        // Tech Solutions Ads (Selling)
        $techProducts = [
            ['title' => 'MacBook Pro M1 2021', 'price' => 1250.00, 'type' => 'sell'],
            ['title' => 'Dell XPS 15 9500', 'price' => 950.00, 'type' => 'sell'],
            ['title' => 'Sony A7III Camera Body', 'price' => 1400.00, 'type' => 'sell'],
            ['title' => 'iPad Air 5th Gen', 'price' => 550.00, 'type' => 'sell'],
        ];

        foreach ($techProducts as $prod) {
            Advertisement::factory()->create([
                'user_id' => $techBusiness->id,
                'title' => $prod['title'],
                'description' => "Condition: Excellent. Includes original packaging and warranty. Perfect for professional use.",
                'price' => $prod['price'],
                'type' => $prod['type'],
            ]);
        }

        // Rent-A-Tool Ads (Renting)
        $toolProducts = [
            ['title' => 'Industrial Drill Hilti', 'price' => 25.00, 'type' => 'rent'], // Price per day
            ['title' => 'Cement Mixer 150L', 'price' => 45.00, 'type' => 'rent'],
            ['title' => 'Scaffolding Set (Small)', 'price' => 60.00, 'type' => 'rent'],
        ];

        foreach ($toolProducts as $prod) {
            Advertisement::factory()->create([
                'user_id' => $toolBusiness->id,
                'title' => $prod['title'],
                'description' => "Available for daily or weekly rental. Deposit required. ID check mandatory upon pickup.",
                'price' => $prod['price'],
                'type' => $prod['type'],
            ]);
        }

        // Private User Ads (Auctions & Sell)
        Advertisement::factory()->create([
            'user_id' => $privateUser->id,
            'title' => 'Vintage Gibson Les Paul',
            'price' => 2500.00, // Starting bid
            'type' => 'auction',
            'expires_at' => now()->addDays(7),
        ]);

        Advertisement::factory()->create([
            'user_id' => $privateUser->id,
            'title' => 'Mountain Bike Trek X-Caliber',
            'price' => 450.00,
            'type' => 'sell',
        ]);

        // Random Filler Data (Background noise)
        User::factory(5)->create()->each(function ($u) {
            if (rand(0,1)) {
                Advertisement::factory(rand(1, 2))->create(['user_id' => $u->id]);
            }
        });

        // ==========================================
        // 4. INTERACTIONS (Bids, Reviews, etc.)
        // ==========================================
        
        $allAds = Advertisement::all();
        $randomUsers = User::where('role', 'user')->get(); // Regular users + created fillers

        foreach ($allAds as $ad) {
            // -- Bids (for auctions) --
            if ($ad->type === 'auction') {
                $basePrice = $ad->price;
                // Create 3 bids, increasing
                Bid::factory()->create(['advertisement_id' => $ad->id, 'user_id' => $regularUser->id, 'amount' => $basePrice + 10]);
                Bid::factory()->create(['advertisement_id' => $ad->id, 'user_id' => $techBusiness->id, 'amount' => $basePrice + 50]); 
                // Let admin bid too why not
                Bid::factory()->create(['advertisement_id' => $ad->id, 'user_id' => User::where('email', 'admin@example.com')->first()->id, 'amount' => $basePrice + 100]);
            }

            // -- Reviews (Product) --
            if (rand(0, 100) < 30) { // 30% chance of review
                Review::factory()->create([
                    'reviewer_id' => $regularUser->id,
                    'reviewable_id' => $ad->id,
                    'reviewable_type' => Advertisement::class,
                    'rating' => rand(3, 5),
                    'comment' => 'Great product, exactly as described!',
                ]);
            }

            // -- Favorites --
            if (rand(0, 1)) {
                $regularUser->favorites()->attach($ad->id);
            }
        }

        // ==========================================
        // 5. AGENDA DATA (Rentals & Expiry Dates)
        // ==========================================

        // Grab tool rental ads for realistic rental periods
        $toolAds = Advertisement::where('user_id', $toolBusiness->id)
            ->where('type', 'rent')
            ->get();

        if ($toolAds->count() >= 3) {
            // Active rental — happening right now
            Rental::factory()->create([
                'advertisement_id' => $toolAds[0]->id,
                'renter_id' => $regularUser->id,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDays(5),
            ]);

            // Upcoming rental — starts next week
            Rental::factory()->create([
                'advertisement_id' => $toolAds[1]->id,
                'renter_id' => $privateUser->id,
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(14),
            ]);

            // Completed rental — ended last week
            Rental::factory()->create([
                'advertisement_id' => $toolAds[2]->id,
                'renter_id' => $regularUser->id,
                'start_date' => now()->subDays(14),
                'end_date' => now()->subDays(7),
            ]);

            // Second active rental on same tool (different period)
            Rental::factory()->create([
                'advertisement_id' => $toolAds[0]->id,
                'renter_id' => $techBusiness->id,
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(17),
            ]);
        }

        // Add expiry dates to tech products for calendar visibility
        $techAds = Advertisement::where('user_id', $techBusiness->id)->get();
        foreach ($techAds as $i => $ad) {
            $ad->update(['expires_at' => now()->addDays(($i + 1) * 10)]); // 10, 20, 30, 40 days out
        }

        // Add expiry dates to tool rental ads
        foreach ($toolAds as $i => $ad) {
            $ad->update(['expires_at' => now()->addDays(($i + 1) * 15)]); // 15, 30, 45 days out
        }

        // Private ads expiry (the mountain bike)
        Advertisement::where('user_id', $privateUser->id)
            ->where('type', 'sell')
            ->update(['expires_at' => now()->addDays(21)]);
    }
}
