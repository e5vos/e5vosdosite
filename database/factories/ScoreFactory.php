<?php

namespace Database\Factories;

use App\Models\Score;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Score::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(0,499),
            'team_id' => $this->faker->numberBetween(0,49),
            'event_id' => $this->faker->numberBetween(0,69),
            'place' => $this->faker->numberBetween(0,69),
        ];
    }
}
