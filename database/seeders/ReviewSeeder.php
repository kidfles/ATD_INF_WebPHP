<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Reviews on Ads (Product Reviews)
        $ads = Advertisement::all();
        $users = User::where('role', 'private_ad')->get();

        if ($users->isEmpty()) return;

        foreach ($ads->take(10) as $ad) {
            Review::create([
                'reviewer_id' => $users->random()->id,
                'reviewable_id' => $ad->id,
                'reviewable_type' => Advertisement::class,
                'rating' => rand(3, 5),
                'comment' => 'Great product, works as expected!',
            ]);
        }

        // Reviews on Sellers (User Reviews)
        $sellers = User::where('role', 'business_ad')->get();

        foreach ($sellers as $seller) {
            Review::create([
                'reviewer_id' => $users->random()->id,
                'reviewable_id' => $seller->id,
                'reviewable_type' => User::class,
                'rating' => rand(4, 5),
                'comment' => 'Excellent service and reliable equipment.',
            ]);
        }
    }
}
