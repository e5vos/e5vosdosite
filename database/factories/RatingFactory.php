<?php

namespace Database\Factories;

use App\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' =>$this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(0, 499),
            'event_id' => $this->faker->numberBetween(0, 30),
        ];
    }
}
