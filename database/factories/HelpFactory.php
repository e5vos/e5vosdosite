<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Help;
use Faker\Generator as Faker;

$factory->define(Help::class, function (Faker $faker) {
    return [
        'user' => $faker->name,
        'help' => $faker->sentence(),
        'solved' => $faker->numberBetween(0,1),
        'deleted' => $faker->numberBetween(0,1),
        'in_progress' => $faker->numberBetween(0,1),
    ];
});
