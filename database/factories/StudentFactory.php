<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory{
    public function definition(){
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'class_id' => $this->faker->numberBetween(1,29),
            'allowed' => $this->faker->boolean()
        ];
    }
}
