<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Presentation;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(Presentation::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'presenter' => $faker->name,
        'description' => $faker->paragraph,
        'capacity' => $faker->numberBetween(10,40),
        'slot'=>    $faker->numberBetween(1,3),
        'code' => $faker->regexify('[A-Za-z0-9]{4}'),
    ];
});
