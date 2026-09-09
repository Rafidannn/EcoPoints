<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin
        User::updateOrCreate(
            ['email' => 'admin@ecopoints.test'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'points_balance' => 0,
                'email_verified_at' => now(),
            ]
        );

        // 2. Petugas Drop Point
        User::updateOrCreate(
            ['email' => 'petugas@ecopoints.test'],
            [
                'name' => 'Petugas Drop Point',
                'password' => Hash::make('password'),
                'role' => UserRole::PETUGAS,
                'points_balance' => 0,
                'email_verified_at' => now(),
            ]
        );

        // 3. Nasabah / Regular Users
        User::updateOrCreate(
            ['email' => 'budi@ecopoints.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'points_balance' => 1500,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti@ecopoints.test'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'points_balance' => 3200,
                'email_verified_at' => now(),
            ]
        );
    }
}
