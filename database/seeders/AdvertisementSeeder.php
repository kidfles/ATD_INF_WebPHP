<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        // Helper om user ID te vinden op e-mail
        $getUserId = fn($email) => User::where('email', $email)->value('id');

        // 1. Mega Store - Paginatie Stress Test
        // Scenario: Genereer 50 advertenties om te verifiëren dat de 'Load More' / paginatie goed werkt.
        // Bedrijfsregel: Standaard sortering is 'Nieuwste eerst', dus we dateren deze in het verleden
        // zodat ze achteraan de lijst komen te staan.
        $megaId = $getUserId('bulk@example.com');
        if ($megaId) {
            Advertisement::factory(50)->create([
                'user_id' => $megaId,
                'image_path' => 'images/placeholders/default.jpg',
                'created_at' => now()->subDays(30),
            ]);
        }

        // 2. TechHub - Verkoop (Sell) Scenario
        // Bedrijfsregel: Producten voor directe verkoop hebben een vaste prijs en geen einddatum (tot verkoop).
        $techId = $getUserId('info@techhub.nl');
        if ($techId) {
            $techAds = [
                ['title' => 'iPhone 14 Pro Max', 'type' => 'sell', 'price' => 1099.00, 'description' => 'Zo goed als nieuw, krasvrij.'],
                ['title' => 'MacBook Air M2', 'type' => 'sell', 'price' => 1250.00, 'description' => 'Slechts 5 laadcycli.'],
                ['title' => 'Sony WH-1000XM5', 'type' => 'sell', 'price' => 299.00, 'description' => 'Noise cancelling koptelefoon.'],
            ];

            foreach ($techAds as $ad) {
                Advertisement::create([
                    'user_id' => $techId,
                    'title' => $ad['title'],
                    'description' => $ad['description'],
                    'price' => $ad['price'],
                    'type' => $ad['type'],
                    'expires_at' => null, // Verkoop-advertenties verlopen niet automatisch
                    'image_path' => 'images/placeholders/tech.jpg',
                ]);
            }
        }

        // 3. BouwGigant - Verhuur (Rent) Scenario
        // Bedrijfsregel: Verhuur gaat per dag. Prijs is dagprijs.
        $bouwId = $getUserId('verhuur@bouwgigant.nl');
        if ($bouwId) {
            $rentAds = [
                ['title' => 'Professionele Stenenknipper', 'price' => 25.00],
                ['title' => 'Kettingzaag Stihl', 'price' => 45.00],
                ['title' => 'Steiger (10 meter)', 'price' => 75.00],
            ];

            foreach ($rentAds as $ad) {
                Advertisement::create([
                    'user_id' => $bouwId,
                    'title' => $ad['title'],
                    'description' => 'Te huur per dag. Professionele kwaliteit.',
                    'price' => $ad['price'],
                    'type' => 'rent',
                    'expires_at' => null, // Verhuur-aanbod blijft staan (in tegenstelling tot een veiling)
                    'image_path' => 'images/placeholders/tools.jpg',
                ]);
            }
        }

        // 4. Vintage Veiling - Veiling (Auction) Scenario
        // Bedrijfsregel: Veilingen hebben een strikte einddatum ('expires_at').
        // Prijs is het 'startbod'.
        $vintageId = $getUserId('info@vintageveiling.nl');
        if ($vintageId) {
            $auctionAds = [
                ['title' => 'Vintage Eames Stoel', 'price' => 150.00],
                ['title' => 'Eerste Editie Harry Potter', 'price' => 500.00],
                ['title' => 'Antieke Klok 1850', 'price' => 75.00],
            ];

            foreach ($auctionAds as $ad) {
                Advertisement::create([
                    'user_id' => $vintageId,
                    'title' => $ad['title'],
                    'description' => 'Bied mee op dit unieke item. Veiling eindigt binnenkort.',
                    'price' => $ad['price'], // Startbod
                    'type' => 'auction',
                    'expires_at' => now()->addDays(7), // Bedrijfsregel: Veiling loopt 7 dagen
                    'image_path' => 'images/placeholders/vintage.jpg',
                ]);
            }
        }
    }
}
