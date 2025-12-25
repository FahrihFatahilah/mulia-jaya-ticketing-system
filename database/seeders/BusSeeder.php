<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $buses = [
            ['code' => 'MJ001', 'name' => 'Mulia Jaya 1 (Mataram-Bima)', 'seat_count' => 32, 'departure_time' => '06:00'],
            ['code' => 'MJ002', 'name' => 'Mulia Jaya 2 (Mataram-Bima)', 'seat_count' => 32, 'departure_time' => '08:00'],
            ['code' => 'MJ003', 'name' => 'Mulia Jaya 3 (Mataram-Bima)', 'seat_count' => 32, 'departure_time' => '14:00'],
            ['code' => 'MJ004', 'name' => 'Mulia Jaya 4 (Bima-Mataram)', 'seat_count' => 32, 'departure_time' => '07:00'],
            ['code' => 'MJ005', 'name' => 'Mulia Jaya 5 (Bima-Mataram)', 'seat_count' => 32, 'departure_time' => '13:00'],
            ['code' => 'MJ006', 'name' => 'Mulia Jaya 6 (Bima-Mataram)', 'seat_count' => 32, 'departure_time' => '16:00'],
        ];

        foreach ($buses as $bus) {
            Bus::create($bus);
        }
    }
}