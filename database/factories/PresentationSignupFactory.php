<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\PresentationSignup;
use Faker\Generator as Faker;

$factory->define(PresentationSignup::class, function (Faker $faker) {
    return [
        'presentation_id' => $faker->numberBetween(0,49),
        'student_id' => $faker->numberBetween(0,69),
        'present' => $faker->boolean(),

    ];
});
