<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'code' => $faker->numberBetween(2015,2020).strtoupper($faker->randomLetter). $faker->numberBetween(10,40).'EJG'.$faker->numberBetween(100,999),
        'name' => $faker->name,
        'class_id' => $faker->numberBetween(1,29),
    ];
});
