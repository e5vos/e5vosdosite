<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'class_id' => $faker->numberBetween(1,29),
        'allowed' => $faker->boolval()
    ];
});
