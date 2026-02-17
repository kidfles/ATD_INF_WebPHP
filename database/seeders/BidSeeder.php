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
        // Get Auction Ads
        $auctions = Advertisement::where('type', 'auction')->get();
        // Get Private Users who bid
        $bidders = User::where('role', 'private_ad')->get();

        if ($auctions->isEmpty() || $bidders->isEmpty()) {
            return;
        }

        foreach ($auctions as $auction) {
            // Place 1-4 bids per auction
            $numBids = rand(1, 4);
            $currentPrice = $auction->price;

            for ($i = 0; $i < $numBids; $i++) {
                $bidder = $bidders->random();
                $bidAmount = $currentPrice + rand(10, 50); // Increment
                
                // Ensure unique user per bid if needed, or allow multiple.
                // Dashboard logic implies "Highest", so we just create them.
                
                Bid::create([
                    'advertisement_id' => $auction->id,
                    'user_id' => $bidder->id,
                    'amount' => $bidAmount,
                    // 'status' => 'accepted' // REMOVED: Schema does not support status. Dashboard calculates it.
                ]);
                
                $currentPrice = $bidAmount;
            }
        }
    }
}
