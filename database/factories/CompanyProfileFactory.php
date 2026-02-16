<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyProfile>
 */
class CompanyProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyName = $this->faker->company();
        return [
            'user_id' => User::factory(),
            'company_name' => $companyName,
            'kvk_number' => $this->faker->numerify('########'),
            'brand_color' => $this->faker->hexColor(),
            'custom_url_slug' => STR($companyName)->slug(),
            'contract_status' => $this->faker->randomElement(['pending', 'approved']),
        ];
    }
}
