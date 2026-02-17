<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        // Helper to get user by email
        $getUserId = fn($email) => User::where('email', $email)->value('id');

        // Mega Store (Bulk Data for Pagination)
        $megaId = $getUserId('bulk@example.com');
        if ($megaId) {
            Advertisement::factory(50)->create([
                'user_id' => $megaId,
                'image_path' => 'images/placeholders/default.jpg', // Default image
            ]);
        }

        // TechHub (Sell - Electronics)
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
                    'expires_at' => null, // Sell ads don't expire
                    'image_path' => 'images/placeholders/tech.jpg',
                ]);
            }
        }

        // BouwGigant (Rent - Tools)
        $bouwId = $getUserId('verhuur@bouwgigant.nl');
        if ($bouwId) {
            $rentAds = [
                ['title' => 'Professionele Stenenknipper', 'price' => 25.00], // Per day
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
                    'expires_at' => null, // Rental listings technically don't expire in the same way, or maybe they do? Assuming no for now.
                    'image_path' => 'images/placeholders/tools.jpg',
                ]);
            }
        }

        // Vintage Veiling (Auction)
        $vintageId = $getUserId('info@vintageveiling.nl');
        if ($vintageId) {
            $auctionAds = [
                ['title' => 'Vintage Eames Stoel', 'price' => 150.00], // Starting bid
                ['title' => 'Eerste Editie Harry Potter', 'price' => 500.00],
                ['title' => 'Antieke Klok 1850', 'price' => 75.00],
            ];

            foreach ($auctionAds as $ad) {
                Advertisement::create([
                    'user_id' => $vintageId,
                    'title' => $ad['title'],
                    'description' => 'Bied mee op dit unieke item. Veiling eindigt binnenkort.',
                    'price' => $ad['price'], // Starting price
                    'type' => 'auction',
                    'expires_at' => now()->addDays(7), // Auctions expire
                    'image_path' => 'images/placeholders/vintage.jpg',
                ]);
            }
        }
    }
}
