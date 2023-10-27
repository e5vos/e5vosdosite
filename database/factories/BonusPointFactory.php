<?php

namespace Database\Factories;

use App\Helpers\EjgClassType;
use App\Models\BonusPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BonusPoint>
 */
class BonusPointFactory extends Factory
{
    protected $model = BonusPoint::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ejg_class' => fake()->randomelement(array_column(EjgClassType::cases(), 'value')),
            'quantity' => fake()->numberBetween(-10, 100),
        ];
    }
}
