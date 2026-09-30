<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@simpro.com'],
            [
                'nama' => 'Admin Dealer Utama',
                'no_hp' => '081298765432',
                'cabang' => 'Yamaha JG Purwakarta',
                'password' => Hash::make('password'),
                'peran' => 'admin',
                'status_aktif' => true,
            ]
        );

        // Sales 1
        User::updateOrCreate(
            ['email' => 'sales1@simpro.com'],
            [
                'nama' => 'Budi Santoso',
                'no_hp' => '081388776655',
                'cabang' => 'Yamaha JG Purwakarta',
                'password' => Hash::make('password'),
                'peran' => 'sales',
                'status_aktif' => true,
            ]
        );

        // Sales 2
        User::updateOrCreate(
            ['email' => 'sales2@simpro.com'],
            [
                'nama' => 'Siti Rahmawati',
                'no_hp' => '085712345678',
                'cabang' => 'Yamaha JG Purwakarta',
                'password' => Hash::make('password'),
                'peran' => 'sales',
                'status_aktif' => true,
            ]
        );
    }
}
