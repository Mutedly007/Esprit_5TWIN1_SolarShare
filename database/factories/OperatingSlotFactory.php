<?php

namespace Database\Factories;

use App\Models\CollectionHub;
use App\Models\OperatingSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OperatingSlot>
 */
class OperatingSlotFactory extends Factory
{
    protected $model = OperatingSlot::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'collection_hub_id' => CollectionHub::factory(),
            'day_of_week' => fake()->randomElement(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']),
            'open_time' => '08:30',
            'close_time' => '18:30',
            'is_active' => true,
            'notes' => fake()->randomElement(['Dépôt & Retrait avec casier sécurisé', 'Permanence avec bénévole sur place', 'Retrait express automatisé', null]),
        ];
    }
}
