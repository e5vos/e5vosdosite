<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory{
    public function definition(){
        return [
            'title' => $this->faker->sentence(),
            'intro' => $this->faker->sentence().$this->faker->sentence(),
            'content' => $this->faker->text(),
            'author' => $this->faker->numberBetween(0,69),
            'publishdate' => $this->faker->date($format='Y-m-d', $max='now'),
        ];
    }
}
