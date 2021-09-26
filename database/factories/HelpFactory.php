<?php

namespace Database\Factories;

use App\Help;
use Illuminate\Database\Eloquent\Factories\Factory;

class HelpFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Help::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user' => $this->faker->name,
            'help' => $this->faker->sentence(),
            'solved' => $this->faker->numberBetween(0,1),
            'deleted' => $this->faker->numberBetween(0,1),
            'in_progress' => $this->faker->numberBetween(0,1),
        ];
    }
}
