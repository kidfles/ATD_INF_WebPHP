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
        // 1. Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // 1b. Standard User (No Ad Rights)
        User::create([
            'name' => 'Standard User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // 2. Private Advertisers
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

        // 3. Business Advertisers (Profiles created in CompanyProfileSeeder)
        $businesses = [
            ['name' => 'TechHub Nederland', 'email' => 'info@techhub.nl'],
            ['name' => 'BouwGigant Verhuur', 'email' => 'verhuur@bouwgigant.nl'],
            ['name' => 'Vintage Veiling Huis', 'email' => 'info@vintageveiling.nl'],
            ['name' => 'Mega Store Outlet', 'email' => 'bulk@example.com'], // For pagination testing

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
