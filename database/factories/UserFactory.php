<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Definieer de standaard staat van het model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // fake() gebruikt nl_NL locale vanwege APP_FAKER_LOCALE instelling
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'user', // Standaard rol
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Staat: E-mailadres is niet geverifieerd.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    
    /**
     * Staat: Gebruiker is een beheerder (Admin).
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
    
    /**
     * Staat: Gebruiker is een zakelijke adverteerder.
     */
    public function businessAdvertiser(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => \App\Enums\UserRole::BusinessSeller,
        ]);
    }
    
    /**
     * Staat: Gebruiker is een particuliere adverteerder.
     */
    public function privateAdvertiser(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'private_ad',
        ]);
    }
}
