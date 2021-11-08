<?php

namespace Database\Factories;

use App\Models\BonusPoint;
use Illuminate\Database\Eloquent\Factories\Factory;
class BonusPointFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BonusPoint::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'class_id' => $this->faker->numberBetween(0,28),
            'point' => $this->faker->numberBetween(5,15),
        ];
    }
}
