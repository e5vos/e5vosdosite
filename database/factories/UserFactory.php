<?php

namespace Database\Factories;

use App\Helpers\EjgClassType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $ejgclass = fake()->randomelement(EjgClassType::cases());
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail() ,
            'google_id' => fake()->random_int(0,10) > 3 ? fake()->unique()->str_random(50) : null,
            'ejg_class' => $ejgclass,
            'img_url' => fake()->random_int(0,10) > 7 ? fake()->imageUrl() : null,
            'e5code' => fake()->random_int(0,10) < 9 ? fake()->unique()->regexify(strval(now()->year()-fake()->random_int(0,6)).'([A-F]{1})([0-9]{2})EJG([0-9]{3})') : null,
        ];
    }
}
