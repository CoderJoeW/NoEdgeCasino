<?php

namespace Database\Seeders;

use App\Models\GlobalPool;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        GlobalPool::create([
            'currency_name' => 'NoEdgeCash',
            'currency_value' => 1000
        ]);
    }
}
