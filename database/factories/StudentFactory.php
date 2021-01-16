<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'code' => $faker->regexify('20[0-9]{2}[A-E]{1}[0-9]{2}EJG[0-9]{3}'),
        'name' => $faker->name,
        'class_id' => $faker->numberBetween(1,29),
        'magantanulo' => $faker->numberBetween(0,1),
    ];
});
