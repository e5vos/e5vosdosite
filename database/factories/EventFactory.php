<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Event;


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
            'code' => $this->faker->unique()->regexify('[A-Za-z0-9]{4}'),
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'location_id' => $this->faker->boolean(20) ? null : $this->faker->numberBetween(0,40),
            'start'=> $this->faker->dateTimeBetween('Y-m-d', '-1 day', '+1 day'),
            'end'=> $this->faker->dateTimeBetween('Y-m-d','-1 day','+1 day'),
            'weight' => $this->faker->boolean(20) ? null : $this->faker->numberBetween(1,3),
            'image_id' => $this->faker->numberBetween(0,100),
            'organiser_id' => $this->faker->numberBetween(0,500),
            'organiser_name' => $this->faker->name(),
            'capacity' => $this->faker->boolean(20) ? null : $this->faker->numberBetween(0,40),
            'slot' => $this->faker->boolean(50) ? null : $this->faker->numberBetween(1,3),
            'is_presentation' =>$this->faker->boolean(20),
        ];
    }
}
