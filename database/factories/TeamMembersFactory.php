<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TeamMember;
use Faker\Generator as Faker;

$factory->define(TeamMember::class, function (Faker $faker) {
    return [
        'team_id' => $faker->numberBetween(0,69),
        'student_id' => $faker->numberBetween(0,420),

    ];
});
