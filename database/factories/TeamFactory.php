<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory{
    public function definition(){
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{2}'),
            'name' => $this->faker->word().$this->faker->word(),
            'admin_id' => $this->faker->numberBetween(0,10),
        ];
    }
}
