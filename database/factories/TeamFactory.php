<?php

namespace Database\Factories;

use App\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{2}'),
            'name' => $this->faker->word().$this->faker->word(),
        ];
    }
}
