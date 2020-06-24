<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EJGClass;
use Faker\Generator as Faker;

$factory->define(EJGClass::class, function (Faker $faker) {
    return [
        'name'=>$faker->numberBetween(7,12).$faker->randomLetter,
        'points'=>$faker->numberBetween(0,1000),
    ];
});
