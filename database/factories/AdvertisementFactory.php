<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertisement>
 */
class AdvertisementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Use a list of real Dutch product names to be realistic
        $products = [
            'Elektrische Fiets', 'Houten Eettafel', 'Iphone 13 Pro', 'Playstation 5', 
            'Wasmachine Bosch', 'Tweedehands Bank', 'Vintage Kast', 'Racefiets Carbon',
            'Laptop HP Pavilion', 'Samsung Galaxy S22', 'Koffiezetapparaat', 'Boormachine Makita'
        ];

        $type = $this->faker->randomElement(['sell', 'rent', 'auction']);

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'title' => $this->faker->randomElement($products) . ' ' . $this->faker->word(),
            'description' => $this->faker->paragraph(3), // Dutch text due to APP_FAKER_LOCALE
            'price' => $this->faker->randomFloat(2, 5, 2500),
            'expires_at' => $type === 'sell' ? null : $this->faker->dateTimeBetween('now', '+3 months'),
        ];
    }
}
