<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CompanyProfile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PageComponent>
 */
class PageComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => CompanyProfile::factory(),
            'component_type' => 'text',
            'content' => ['title' => $this->faker->sentence(), 'body' => $this->faker->paragraph()],
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
