<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EJGClassFactory extends Factory{
    public function definition(){
        return [
            'name'=>$this->faker->numberBetween(7,12).$this->faker->randomLetter,
            'points'=>$this->faker->numberBetween(0,1000),
        ];

    }
}

