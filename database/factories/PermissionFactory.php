<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory{
    public function definition(){
        return [
            'user_id' => $this->faker->numberBetween(0,499),
            'event_id' => $this->faker->numberBetween(0,49),
            'permission' => $this->faker->asciify('********************').$this->faker->asciify('********************'),
        ];
    }
}
