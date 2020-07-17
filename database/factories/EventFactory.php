<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'description' => $faker->paragraph,
        'location_id' => $faker->numberBetween(0,40),
        'start'=>    $faker->date($format='Y-m-d', $max='now'),
        'end'=>    $faker->date($format='Y-m-d', $max='now'),
        'weight' => $faker->numberBetween(1,3),
        'image_id' => $faker->numberBetween(0,100),
    ];
});
