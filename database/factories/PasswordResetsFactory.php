<?php
namespace Database\Factories;
use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Str;




$factory->define(Model::class, function (Faker $this->faker) {
    return [
        'email' => $this->faker->safeEmail(),
        'token' => Str::random(10),
    ];
});
