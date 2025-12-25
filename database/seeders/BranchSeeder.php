<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['name' => 'Mataram', 'code' => 'MTR', 'address' => 'Jl. Raya Mataram No. 1', 'phone' => '0370-1234567'],
            ['name' => 'Bima', 'code' => 'BMA', 'address' => 'Jl. Raya Bima No. 1', 'phone' => '0374-1234567'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}