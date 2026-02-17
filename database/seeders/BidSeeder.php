<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\User;
use App\Models\Bid;
use Illuminate\Database\Seeder;

class BidSeeder extends Seeder
{
    public function run(): void
    {
        // Stap 1: Haal alle lopende veilingen op
        $auctions = Advertisement::where('type', 'auction')->get();
        // Stap 2: Haal particuliere gebruikers op die kunnen bieden
        $bidders = User::where('role', 'private_ad')->get();

        if ($auctions->isEmpty() || $bidders->isEmpty()) {
            return;
        }

        foreach ($auctions as $auction) {
            // Scenario: Simuleer een actief biedproces met 1 tot 4 biedingen per veiling.
            $numBids = rand(1, 4);
            $currentPrice = $auction->price;

            for ($i = 0; $i < $numBids; $i++) {
                $bidder = $bidders->random();
                
                // Bedrijfsregel: Een nieuw bod moet altijd hoger zijn dan de huidige prijs.
                // We verhogen het bod hier met een willekeurig bedrag tussen 10 en 50.
                $bidAmount = $currentPrice + rand(10, 50); 
                
                Bid::create([
                    'advertisement_id' => $auction->id,
                    'user_id' => $bidder->id,
                    'amount' => $bidAmount,
                    // Opmerking: Status veld is niet nodig. Dashboard bepaalt het hoogste bod dynamisch.
                ]);
                
                // Update de huidige prijs voor de volgende iteratie in de loop
                $currentPrice = $bidAmount;
            }
        }
    }
}
