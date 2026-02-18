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

        $calculator = app(\App\Services\WearAndTearCalculator::class);

        foreach ($rentAds as $ad) {
            // Scenario: 50% kans dat een verhuur-item daadwerkelijk verhuurd is geweest in het verleden.
            if (rand(0, 1)) {
                $renter = $renters->random();
                
                // Maak een AFGERONDE verhuur historie aan
                $rental = new Rental([
                    'advertisement_id' => $ad->id,
                    'renter_id' => $renter->id,
                    'start_date' => now()->subDays(rand(10, 15)),
                    'end_date' => now()->subDays(rand(5, 9)),
                    'return_photo_path' => 'images/placeholder/return.png',
                ]);

                // Gebruik de calculator om realistische data te genereren (inclusief mogelijke boetes)
                $result = $calculator->calculate($rental);
                
                $rental->total_price = $result['breakdown']['base_cost'];
                $rental->wear_and_tear_cost = $result['breakdown']['wear_and_tear'];
                $rental->total_cost = $result['total'];
                
                // Simuleer af en toe een late fee door de datum handmatig te pushen in de calculator context
                // maar voor de seeder houden we het nu simpel en consistent.
                $rental->save();
            }
        }
    }
}
