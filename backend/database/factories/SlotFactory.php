<?php

namespace Database\Factories;

use App\Helpers\SlotType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Slot;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SlotFactory extends Factory
{
    protected $model = Slot::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $starts_at = fake()->dateTimeBetween('now', '+1 year');
        $type = fake()->randomElement(array_column(SlotType::cases(), 'value'));
        return [
            'name' => fake()->word().' '.$type,
            'starts_at' => $starts_at,
            'ends_at' => fake()->dateTimeBetween($starts_at, '+1 year'),
            'slot_type' => $type,
        ];
    }
}
