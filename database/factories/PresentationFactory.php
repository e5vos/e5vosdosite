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
    ];
});
