<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'description' => $faker->paragraph,
        'location_id' => $faker->numberBetween(0,40),
        'start'=> $faker->dateTimeBetween($format = 'Y-m-d', $startDate = '-1 day', $endDate = '+1 day'),
        'end'=> $faker->dateTimeBetween($format = 'Y-m-d', $startDate = '-1 day', $endDate = '+1 day'),
        'weight' => $faker->numberBetween(1,3),
        'image_id' => $faker->numberBetween(0,100),
    ];
});
