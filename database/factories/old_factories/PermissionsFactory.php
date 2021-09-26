<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(0,499),
        'event_id' => $faker->numberBetween(0,49),
        'permission' => $faker->asciify('********************').$faker->asciify('********************'),
    ];
});
