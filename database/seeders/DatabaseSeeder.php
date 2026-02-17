<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Vul de database met testdata.
     * 
     * Volgorde is cruciaal vanwege Foreign Key constraints:
     * 1. Users (Basis entiteiten)
     * 2. CompanyProfiles (Breidt zakelijke users uit)
     * 3. PageComponents (Afhankelijk van CompanyProfiles)
     * 4. Advertisements (Afhankelijk van Users)
     * 5. Bids/Rentals/Reviews (Interacties op Advertenties/Users)
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanyProfileSeeder::class,
            PageComponentSeeder::class,
            AdvertisementSeeder::class,
            BidSeeder::class,
            RentalSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
