<?php

namespace Database\Factories;

use App\EJGClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class EJGClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EJGClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->numberBetween(7,12).$this->faker->randomLetter,
            'points'=>$this->faker->numberBetween(0,1000),
        ];
    }
}
