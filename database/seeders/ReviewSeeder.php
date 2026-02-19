<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Enums\UserRole;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reviews op Producten (Advertenties)
        // Scenario: Gebruikers laten reviews achter op specifieke items die ze hebben gebruikt.
        $ads = Advertisement::all();
        $users = User::where('role', UserRole::PrivateSeller)->get();

        if ($users->isEmpty()) return;

        foreach ($ads->take(10) as $ad) {
            Review::create([
                'reviewer_id' => $users->random()->id,
                'reviewable_id' => $ad->id,
                'reviewable_type' => Advertisement::class, // Polymorfe koppeling naar Advertentie
                'rating' => rand(3, 5),
                'comment' => 'Geweldig product, werkt zoals verwacht!',
            ]);
        }

        // 2. Reviews op Verkopers (Gebruikers)
        // Scenario: Gebruikers beoordelen de verkoper zelf op basis van service en betrouwbaarheid.
        $sellers = User::where('role', UserRole::BusinessSeller)->get();

        foreach ($sellers as $seller) {
            Review::create([
                'reviewer_id' => $users->random()->id,
                'reviewable_id' => $seller->id,
                'reviewable_type' => User::class, // Polymorfe koppeling naar User
                'rating' => rand(4, 5),
                'comment' => 'Uitstekende service en betrouwbare apparatuur.',
            ]);
        }
    }
}
