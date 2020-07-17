<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Team;
use Faker\Generator as Faker;

$factory->define(Team::class, function (Faker $faker) {
    return [
        'code' => $faker->regexify('[A-Z]{2}[0-9]{2}'),
        'name' => $faker->word().$faker->word(),
        'admin_id' => $faker->numberBetween(0,10),
    ];
});
