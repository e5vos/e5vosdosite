<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;


class PresentationFactory extends Factory{
    public function definition() {
        return [
            'title' => $this->faker->sentence,
            'presenter' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'capacity' => $this->faker->numberBetween(10,40),
            'slot'=>    $this->faker->numberBetween(1,3),
            'code' => $this->faker->regexify('[A-Za-z0-9]{4}'),
        ];
    }
}
