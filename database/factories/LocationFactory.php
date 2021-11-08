<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;


class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'floor' => $this->faker->numberBetween(0,3),
            'name' => $this->faker->numberBetween(0,3).$this->faker->numberBetween(00,20).'. terem',
        ];
    }
}
