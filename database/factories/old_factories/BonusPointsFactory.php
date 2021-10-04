<?php
namespace Database\Factories;

<<<<<<< HEAD:database/factories/old_factories/BonusPointsFactory.php
namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
=======
use Illuminate\Database\Eloquent\Factories\Factory;
>>>>>>> dev:database/factories/BonusPointsFactory.php

class BonusPointsFactory extends Factory{
    public function definition(){
        return [
            'class_id' => $this->faker->numberBetween(0,28),
            'point' => $this->faker->numberBetween(5,15),
        ];
    }
}
