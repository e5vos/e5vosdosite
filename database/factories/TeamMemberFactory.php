<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeamMemberFactory extends Factory{
    public function definition(){
        return [
            'team_id' => $this->faker->numberBetween(0,69),
            'student_id' => $this->faker->numberBetween(0,420),

        ];
    }
}
