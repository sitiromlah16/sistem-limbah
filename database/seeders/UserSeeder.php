<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Operator
        User::updateOrCreate(
            ['email' => 'operator@pabrik.com'],
            [
                'name' => 'Budi Operator',
                'password' => Hash::make('password123'),
                'role' => 'operator',
            ],
        );

        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@pabrik.com'],
            [
                'name' => 'Siti Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
        );

        // Akun Manajer
        User::updateOrCreate(
            ['email' => 'manajer@pabrik.com'],
            [
                'name' => 'Andi Manajer',
                'password' => Hash::make('password123'),
                'role' => 'manajer',
            ],
        );
    }
}