<?php

namespace Database\Factories;

use App\Models\CollectionHub;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CollectionHub>
 */
class CollectionHubFactory extends Factory
{
    protected $model = CollectionHub::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city = fake()->randomElement(['Tunis', 'Ariana', 'La Marsa', 'Sousse', 'Sfax', 'Bizerte', 'Nabeul']);
        $hubTypes = ['Casier Solaire Connecté', 'Hub Éco-Relais', 'Station Solaire Partenaire', 'Point Relais Vert'];
        $name = fake()->randomElement($hubTypes) . ' — ' . $city . ' ' . fake()->streetName();

        return [
            'name' => $name,
            'address' => fake()->streetAddress(),
            'city' => $city,
            'postal_code' => (string) fake()->numberBetween(1000, 9000),
            'contact_phone' => '+216 ' . fake()->randomElement(['20', '21', '22', '25', '50', '98']) . ' ' . fake()->numerify('### ###'),
            'max_capacity' => fake()->numberBetween(10, 40),
            'description' => fake()->paragraph(2),
            'is_active' => true,
        ];
    }
}
