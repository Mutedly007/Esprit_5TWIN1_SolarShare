<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = ['EcoFlow', 'Bluetti', 'Jackery', 'Renogy', 'Victron Energy', 'Automaxx', 'BioLite', 'Anker'];
        $brand = fake()->randomElement($brands);

        $name = fake()->randomElement([
            'Panneau Solaire Pliable 200W',
            'Panneau Rigide Haute Efficacité 400W',
            'Station d\'Énergie Portable 1024Wh',
            'Batterie Lithium Nomade 2048Wh',
            'Micro-Éolienne Portable 500W',
            'Onduleur Pur Sinus 12V/230V 1000W',
            'Valise Solaire Autonome 300W',
        ]);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => $name,
            'brand' => $brand,
            'description' => fake()->paragraph(2),
            'power_watts' => fake()->randomElement([100, 200, 400, 500, 800, 1000]),
            'capacity_wh' => fake()->randomElement([null, 512, 1024, 1536, 2048]),
            'daily_rate' => fake()->randomFloat(2, 12, 50),
            'deposit_amount' => fake()->randomFloat(2, 100, 400),
            'is_available' => true,
            'status' => 'pending', // Automatic default
        ];
    }

    /**
     * Indicate that the equipment is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the equipment is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    /**
     * Indicate that the equipment is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }
}
