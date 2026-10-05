<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Do not automatically create dummy users or dummy equipment
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
