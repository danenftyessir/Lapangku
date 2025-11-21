<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk create sample company data untuk development
 */
class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create verified tech companies
        $companies = [
            [
                'name' => 'TechCorp Indonesia',
                'email' => 'contact@techcorp.id',
                'username' => 'techcorp',
                'industry' => 'Teknologi Informasi',
                'description' => 'Perusahaan teknologi terkemuka yang fokus pada pengembangan solusi digital untuk berbagai industri.',
                'employee_count' => 500,
                'founded_year' => 2015,
            ],
            [
                'name' => 'StartupHub Jakarta',
                'email' => 'hello@startuphub.co.id',
                'username' => 'startuphub',
                'industry' => 'E-Commerce',
                'description' => 'Platform e-commerce yang menghubungkan UMKM dengan konsumen di seluruh Indonesia.',
                'employee_count' => 200,
                'founded_year' => 2018,
            ],
            [
                'name' => 'DigitalAgency Pro',
                'email' => 'info@digitalagency.id',
                'username' => 'digitalagency',
                'industry' => 'Digital Marketing',
                'description' => 'Agensi digital yang menyediakan layanan marketing, branding, dan pengembangan web.',
                'employee_count' => 50,
                'founded_year' => 2019,
            ],
        ];

        foreach ($companies as $companyData) {
            // Create user account for company
            $user = User::create([
                'name' => $companyData['name'],
                'email' => $companyData['email'],
                'username' => $companyData['username'],
                'password' => Hash::make('password123'),
                'user_type' => 'company',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Create company profile
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $companyData['name'],
                'industry' => $companyData['industry'],
                'description' => $companyData['description'],
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $companyData['username'])) . '.com',
                'address' => 'Jl. Sudirman No. ' . rand(10, 100),
                'city' => 'Jakarta',
                'phone' => '+628' . rand(1000000000, 9999999999),
                'employee_count' => $companyData['employee_count'],
                'founded_year' => $companyData['founded_year'],
                'verification_status' => 'verified',
                'verified_at' => now(),
            ]);

            // Create 3-5 job postings for each company
            JobPosting::factory()
                ->count(rand(3, 5))
                ->for($company)
                ->open()
                ->create();

            $this->command->info("✅ Created company: {$company->name} with job postings");
        }

        // Create additional random companies using factory
        Company::factory()
            ->count(5)
            ->verified()
            ->create()
            ->each(function ($company) {
                // Create 2-4 job postings for each random company
                JobPosting::factory()
                    ->count(rand(2, 4))
                    ->for($company)
                    ->open()
                    ->create();

                $this->command->info("✅ Created random company: {$company->name}");
            });

        $this->command->info("✅ CompanySeeder completed!");
    }
}
