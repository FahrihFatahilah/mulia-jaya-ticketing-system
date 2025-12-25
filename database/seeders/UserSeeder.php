<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@muliajaya.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kantor Bima',
            'email' => 'ktr.bima@muliajaya.com',
            'password' => Hash::make('password'),
            'role' => 'loket',
            'office' => 'bima',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kantor Mataram',
            'email' => 'ktr.mataram@muliajaya.com',
            'password' => Hash::make('password'),
            'role' => 'loket',
            'office' => 'mataram',
            'is_active' => true,
        ]);
    }
}