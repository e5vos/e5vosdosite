<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Score;
use Faker\Generator as Faker;

$factory->define(Score::class, function (Faker $faker) {
    return [
        'student_id' => $faker->numberBetween(0,499),
        'team_id' => $faker->numberBetween(0,49),
        'event_id' => $faker->numberBetween(0,69),
        'place' => $faker->numberBetween(0,69),

    ];
});
