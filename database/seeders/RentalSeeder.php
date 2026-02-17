<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        // Get Rent Ads
        $rentAds = Advertisement::where('type', 'rent')->get();
        // Get Private Users who rent
        $renters = User::where('role', 'private_ad')->get();

        if ($rentAds->isEmpty() || $renters->isEmpty()) {
            return;
        }

        foreach ($rentAds as $ad) {
            // 50% chance to have a rental history
            if (rand(0, 1)) {
                $renter = $renters->random();
                
                // Past Rental (Completed with Return Info)
                Rental::create([
                    'advertisement_id' => $ad->id,
                    'renter_id' => $renter->id,
                    'start_date' => now()->subDays(10),
                    'end_date' => now()->subDays(5),
                    'wear_and_tear_cost' => rand(0, 1) ? 15.00 : null, // sometimes damanged
                    'return_photo_path' => 'images/placeholders/return.jpg',
                ]);
            }
        }
    }
}
