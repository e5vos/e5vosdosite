<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BonusPoints;
use Faker\Generator as Faker;

$factory->define(BonusPoints::class, function (Faker $faker) {
    return [
        'class_id' => $faker->numberBetween(0,28),
        'point' => $faker->numberBetween(5,15),
    ];
});
