<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BranchSeeder::class,
            RouteSeeder::class,
            BusSeeder::class,
        ]);
    }
}