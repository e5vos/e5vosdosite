<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->title,
        'description' => $faker->paragraph,
        'location_id' => $faker->numberBetween(0,40),
        'start'=>    $faker->date($format='Y-m-D', $max='now'),
        'end'=>    $faker->date($format='Y-m-D', $max='now'),
        'created_at'=>    $faker->date($format='Y-m-D', $max='now'),
        'updated_at'=>    $faker->date($format='Y-m-D', $max='now'),
        'weight' => $faker->numberBetween(1,3),
        'image_id' => $faker->numberBetween(0,100),
    ];
});
