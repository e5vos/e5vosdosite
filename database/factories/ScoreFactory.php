<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ScoreFactory extends Factory{
    public function definition(){
        return [
            'student_id' => $this->faker->numberBetween(0,499),
            'team_id' => $this->faker->numberBetween(0,49),
            'event_id' => $this->faker->numberBetween(0,69),
            'place' => $this->faker->numberBetween(0,69),

        ];
    }
}

