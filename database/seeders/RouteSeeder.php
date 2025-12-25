<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $mataram = Branch::where('code', 'MTR')->first();
        $bima = Branch::where('code', 'BMA')->first();

        $routes = [
            ['origin_branch_id' => $bima->id, 'destination_branch_id' => $mataram->id, 'price' => 0],
            ['origin_branch_id' => $mataram->id, 'destination_branch_id' => $bima->id, 'price' => 0],
        ];

        foreach ($routes as $route) {
            Route::create($route);
        }
    }
}