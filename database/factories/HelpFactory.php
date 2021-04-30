<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HelpFactory extends Factory{
    public function definition(){
        return [
            'user' => $this->faker->name,
            'help' => $this->faker->sentence(),
            'solved' => $this->faker->numberBetween(0,1),
            'deleted' => $this->faker->numberBetween(0,1),
            'in_progress' => $this->faker->numberBetween(0,1),
        ];
    }
}
