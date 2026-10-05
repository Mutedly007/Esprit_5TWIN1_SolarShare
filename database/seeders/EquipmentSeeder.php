<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create default demo accounts for testing and grading
        $admin = User::firstOrCreate(
            ['email' => 'admin@solarshare.tn'],
            [
                'name' => 'Admin SolarShare',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'solar_role' => 'admin',
            ]
        );

        $lender = User::firstOrCreate(
            ['email' => 'lender@solarshare.tn'],
            [
                'name' => 'Mohamed Prêteur',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'solar_role' => 'lend',
            ]
        );

        $renter = User::firstOrCreate(
            ['email' => 'renter@solarshare.tn'],
            [
                'name' => 'Sarra Emprunteuse',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'solar_role' => 'rent',
            ]
        );

        // 2. Ensure categories exist
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        // 3. Seed realistic equipment for the demo lender using EquipmentFactory
        foreach ($categories as $category) {
            // Approved and available equipment
            Equipment::factory()->count(2)->approved()->create([
                'user_id' => $lender->id,
                'category_id' => $category->id,
                'is_available' => true,
            ]);

            // Approved but currently rented / unavailable equipment
            Equipment::factory()->count(1)->approved()->unavailable()->create([
                'user_id' => $lender->id,
                'category_id' => $category->id,
            ]);
        }

        // 4. Also seed a couple of equipment from other lenders to test filtering & isolation
        $otherLender = User::firstOrCreate(
            ['email' => 'eco.lender@solarshare.tn'],
            [
                'name' => 'Éco Partage Tunisie',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'solar_role' => 'lend',
            ]
        );

        foreach ($categories->take(2) as $category) {
            Equipment::factory()->approved()->create([
                'user_id' => $otherLender->id,
                'category_id' => $category->id,
                'is_available' => true,
            ]);
        }
    }
}
