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
        // Stap 1: Haal advertenties van het type 'huur'
        $rentAds = Advertisement::where('type', 'rent')->get();
        // Stap 2: Haal potentiële huurders
        $renters = User::where('role', 'private_ad')->get();

        if ($rentAds->isEmpty() || $renters->isEmpty()) {
            return;
        }

        foreach ($rentAds as $ad) {
            // Scenario: 50% kans dat een verhuur-item daadwerkelijk verhuurd is geweest in het verleden.
            if (rand(0, 1)) {
                $renter = $renters->random();
                
                // Maak een AFGERONDE verhuur historie aan
                Rental::create([
                    'advertisement_id' => $ad->id,
                    'renter_id' => $renter->id,
                    'start_date' => now()->subDays(10),
                    'end_date' => now()->subDays(5),
                    
                    // Bedrijfsregel: Schade/Slijtage (Wear & Tear)
                    // Als er schade is, wordt er een bedrag gerekend. Hier simuleren we 50% kans op schade.
                    'wear_and_tear_cost' => rand(0, 1) ? 15.00 : null, 
                    'return_photo_path' => 'images/placeholders/return.jpg',
                ]);
            }
        }
    }
}
