<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@pas.local');
        $password = (string) env('ADMIN_PASSWORD', 'admin12345');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'role' => 'super admin',
            ],
        );

        User::updateOrCreate(
            ['email' => 'sales@example.com'],
            [
                'name' => 'Sales Demo',
                'password' => Hash::make('password123'),
                'role' => 'sales',
            ],
        );
    }
}
