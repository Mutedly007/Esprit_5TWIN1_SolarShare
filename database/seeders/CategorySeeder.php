<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Panneaux solaires portables',
                'slug' => 'panneaux-solaires-portables',
                'description' => 'Panneaux solaires pliables et valises solaires légères pour camping, chantiers et urgences.',
            ],
            [
                'name' => 'Stations d\'énergie / Batteries',
                'slug' => 'stations-energie-batteries',
                'description' => 'Batteries nomades LiFePO4 et générateurs solaires portables pour stocker l\'énergie propre.',
            ],
            [
                'name' => 'Micro-éoliennes',
                'slug' => 'micro-eoliennes',
                'description' => 'Équipements éoliens compacts faciles à déployer pour production électrique complémentaire.',
            ],
            [
                'name' => 'Onduleurs nomades',
                'slug' => 'onduleurs-nomades',
                'description' => 'Convertisseurs de tension 12V/24V vers 230V pur sinus et régulateurs de charge solaires.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}
