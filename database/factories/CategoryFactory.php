<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $baseName = fake()->randomElement([
            'Panneaux solaires portables',
            'Stations d\'énergie / Batteries',
            'Micro-éoliennes',
            'Onduleurs nomades',
            'Kits solaires hybrides',
            'Régulateurs MPPT',
        ]);
        $name = $baseName . ' ' . fake()->unique()->numberBetween(100, 9999);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(10),
        ];
    }
}
