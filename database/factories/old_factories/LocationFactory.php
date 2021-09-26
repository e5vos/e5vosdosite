<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Location;
use Faker\Generator as Faker;

$factory->define(Location::class, function (Faker $faker) {
    return [
        'floor' => $faker->numberBetween(0,3),
        'name' => $faker->numberBetween(0,3).$faker->numberBetween(00,20).'. terem',
    ];
});
