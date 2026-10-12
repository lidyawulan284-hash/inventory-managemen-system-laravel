<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@inventory.com'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        // Buat Akun Staff
        User::updateOrCreate(
            ['email' => 'staff@inventory.com'],
            [
                'name' => 'Staff Gudang',
                'username' => 'staff',
                'role' => 'staff',
                'password' => Hash::make('password123'),
            ]
        );
    }
}