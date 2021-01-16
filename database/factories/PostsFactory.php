<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'intro' => $faker->sentence().$faker->sentence(),
        'content' => $faker->text(),
        'author' => $faker->numberBetween(0,69),
        'publishdate' => $faker->date($format='Y-m-d', $max='now'),
    ];
});
