<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BonusPointsFactory extends Factory{
    public function definition(){
        return [
            'class_id' => $this->faker->numberBetween(0,28),
            'point' => $this->faker->numberBetween(5,15),
        ];
    }
}
