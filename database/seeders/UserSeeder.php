<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Gebruiker
        // Rol: 'admin' - Heeft volledige toegang tot het dashboard en beheertaken.
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // 1b. Standaard Gebruiker
        // Rol: 'user' - Kan alleen kijken, kopen en huren. Mag GEEN advertenties plaatsen.
        User::create([
            'name' => 'Standard User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // 2. Particuliere Adverteerders
        // Rol: 'private_ad' - Mag advertenties plaatsen maar heeft geen bedrijfspagina.
        // Scenario: Testen van individuele verkopen en verhuur zonder KvK.
        $privateUsers = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['name' => 'Mark Rutte', 'email' => 'mark@example.com'],
        ];

        foreach ($privateUsers as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => 'private_ad',
                'email_verified_at' => now(),
            ]);
        }

        // 3. Zakelijke Adverteerders
        // Rol: 'business_ad' - Mag advertenties plaatsen EN heeft een bedrijfsprofiel + pagina.
        // Koppeling: Profielen worden aangemaakt in CompanyProfileSeeder.
        $businesses = [
            ['name' => 'TechHub Nederland', 'email' => 'info@techhub.nl'],
            ['name' => 'BouwGigant Verhuur', 'email' => 'verhuur@bouwgigant.nl'],
            ['name' => 'Vintage Veiling Huis', 'email' => 'info@vintageveiling.nl'],
            ['name' => 'Mega Store Outlet', 'email' => 'bulk@example.com'], // Scenario: Paginatie testen met veel data
        ];

        foreach ($businesses as $business) {
            User::create([
                'name' => $business['name'],
                'email' => $business['email'],
                'password' => Hash::make('password'),
                'role' => 'business_ad',
                'email_verified_at' => now(),
            ]);
        }
    }
}
