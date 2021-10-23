<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

namespace Database\Factories;


class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph,
            'location_id' => $this->faker->numberBetween(0,40),
            'start'=> $this->faker->dateTimeBetween($format = 'Y-m-d', $startDate = '-1 day', $endDate = '+1 day'),
            'end'=> $this->faker->dateTimeBetween($format = 'Y-m-d', $startDate = '-1 day', $endDate = '+1 day'),
            'weight' => $this->faker->numberBetween(1,3),
            'image_id' => $this->faker->numberBetween(0,100),
        ];
    }
}
