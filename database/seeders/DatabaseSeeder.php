<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Keep this production-safe (no factories/faker)
        $this->call([
            ProductionSeeder::class,
        ]);
    }
}
