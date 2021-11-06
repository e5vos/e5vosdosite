<?php

namespace Database\Factories;

use App\Models\Rating;
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
            'rating' =>$this->faker->randomFloat(3,0,1),
            'user_id' => $this->faker->numberBetween(0,499),
            'event_id' => $this->faker->numberBetween(0,30),
        ];
    }
}
