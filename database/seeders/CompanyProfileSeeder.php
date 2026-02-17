<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            'info@techhub.nl' => [
                'company_name' => 'TechHub Nederland',
                'kvk_number' => '12345678',
                'brand_color' => '#3b82f6', // Blue
                'custom_url_slug' => 'techhub',
                'contract_status' => 'approved',
                'contract_file_path' => 'contracts/techhub_signed.pdf',
            ],
            'verhuur@bouwgigant.nl' => [
                'company_name' => 'BouwGigant Verhuur',
                'kvk_number' => '87654321',
                'brand_color' => '#f59e0b', // Amber
                'custom_url_slug' => 'bouwgigant',
                'contract_status' => 'pending', // Testing pending logic
                'contract_file_path' => null,
            ],
            'info@vintageveiling.nl' => [
                'company_name' => 'Vintage Veiling Huis',
                'kvk_number' => '56781234',
                'brand_color' => '#8b5cf6', // Violet
                'custom_url_slug' => 'vintage-veiling',
                'contract_status' => 'approved',
                'contract_file_path' => 'contracts/vintage_signed.pdf',
            ],
             'bulk@example.com' => [
                'company_name' => 'Mega Store Outlet',
                'kvk_number' => '90000003',
                'brand_color' => '#ef4444', // Red
                'custom_url_slug' => 'mega-store',
                'contract_status' => 'approved',
                'contract_file_path' => 'contracts/mega_signed.pdf',
            ],
        ];

        foreach ($profiles as $email => $data) {
            $user = User::where('email', $email)->first();
            if ($user && !$user->companyProfile) {
                CompanyProfile::create(array_merge($data, ['user_id' => $user->id]));
            }
        }
    }
}
