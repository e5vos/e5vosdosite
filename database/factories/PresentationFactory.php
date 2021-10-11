<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Presentation;

class PresentationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Presentation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'presenter' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'capacity' => $this->faker->numberBetween(10,40),
            'slot'=>    $this->faker->numberBetween(1,3),
            'code' => $this->faker->unique()->regexify('[A-Za-z0-9]{4}'),
        ];
    }
}
