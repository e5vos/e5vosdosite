<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory{
    public function definition(){
        return [
            'floor' => $this->faker->numberBetween(0,3),
            'name' => $this->faker->numberBetween(0,3).$this->faker->numberBetween(00,20).'. terem',
        ];
    }
}
