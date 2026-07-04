<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\CompanyProfile;
use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '9800000000',
            'password' => 'password',
        ]);
        $admin->assignRole(RoleEnum::Admin);

        // Company
        $company = User::create([
            'name' => 'Company Owner',
            'email' => 'company@example.com',
            'phone' => '9800000001',
            'password' => 'password',
        ]);
        $company->assignRole(RoleEnum::Company);
        CompanyProfile::create([
            'user_id' => $company->id,
            'company_name' => 'Test Bike Rental',
            'owner_name' => 'Company Owner',
            'contact_number' => '9800000001',
            'verification_status' => 'verified',
        ]);

        // Customer
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'phone' => '9800000002',
            'password' => 'password',
        ]);
        $customer->assignRole(RoleEnum::Customer);
        CustomerProfile::create([
            'user_id' => $customer->id,
            'full_name' => 'Customer User',
            'verification_status' => 'verified',
        ]);
    }
}
