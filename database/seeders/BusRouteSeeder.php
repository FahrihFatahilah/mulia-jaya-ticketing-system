<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;
use App\Models\Route;

class BusRouteSeeder extends Seeder
{
    public function run(): void
    {
        // Get all buses and routes
        $buses = Bus::all();
        $routes = Route::with(['originBranch', 'destinationBranch'])->get();

        foreach ($buses as $bus) {
            $busName = strtolower($bus->name);
            
            foreach ($routes as $route) {
                $origin = strtolower($route->originBranch->name);
                $destination = strtolower($route->destinationBranch->name);
                
                // Match bus to route if bus name contains origin or destination
                if (str_contains($busName, $origin) || str_contains($busName, $destination)) {
                    $bus->routes()->syncWithoutDetaching([$route->id]);
                }
            }
        }
    }
}