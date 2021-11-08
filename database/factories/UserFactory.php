<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'google_id' => Str::random(64),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'class_id' =>$this->faker->numberBetween(0,29),
            'remember_token' => Str::random(10),
        ];
    }
}
