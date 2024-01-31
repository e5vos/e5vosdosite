<?php

namespace Database\Factories;

use App\Helpers\EjgClassType;
use App\Models\User;
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
        $ejgclass = fake()->randomelement(array_column(EjgClassType::cases(), 'value'));

        return [
            'name' => fake()->name('male'),
            'email' => fake()->unique()->safeEmail(),
            'google_id' => rand(0, 10) > 3 ? fake()->unique()->asciify('*******************************') : null,
            'ejg_class' => $ejgclass,
            'img_url' => rand(0, 10) > 7 ? fake()->imageUrl() : null,
            'e5code' => rand(0, 10) < 9 ? fake()->unique()->regexify('20[0-9]{2}[A-FN]{1}[0-9]{2}EJG[0-9]{3}$') : null,
        ];
    }
}
