<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\AdvertisementType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertisement>
 */
class AdvertisementFactory extends Factory
{
    /**
     * Definieer de standaard staat van het model.
     * 
     * Genereert realistische Nederlandse advertenties met willekeurige types 
     * (verkoop, verhuur, veiling) en bijbehorende prijzen.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lijst met realistische Nederlandse productnamen
        $products = [
            'Elektrische Fiets', 'Houten Eettafel', 'Iphone 13 Pro', 'Playstation 5', 
            'Wasmachine Bosch', 'Tweedehands Bank', 'Vintage Kast', 'Racefiets Carbon',
            'Laptop HP Pavilion', 'Samsung Galaxy S22', 'Koffiezetapparaat', 'Boormachine Makita'
        ];

        $type = $this->faker->randomElement(AdvertisementType::cases());

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'title' => $this->faker->randomElement($products) . ' ' . $this->faker->word(),
            'description' => $this->faker->paragraph(3), // Nederlandse tekst via APP_FAKER_LOCALE
            'price' => $this->faker->randomFloat(2, 5, 2500),
            // Bedrijfsregel: Verkoop advertenties hebben geen verloopdatum, veilingen wel.
            'expires_at' => $type === AdvertisementType::Sale ? null : $this->faker->dateTimeBetween('now', '+3 months'),
        ];
    }
}
