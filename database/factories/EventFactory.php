<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
   protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $starts_at = fake()->dateTimeBetween('now', '+3 days');
        return [
            'name' => fake()->name(),
            'description' => fake()->paragraph(),
            'starts_at' => $starts_at,
            'ends_at' => fake()->dateTimeBetween($starts_at, '+3 days'),
            'signup_deadline' => fake()->dateTimeBetween('-3 days', $starts_at),
            'capacity' =>  fake()->boolean() ? null : fake()->random_int(1, 30),
            'location_id' => null,
            'slot_id' => null,
            'img_url' => fake()->random_int(0,10) > 7 ? fake()->imageUrl() : null,
            'signup_type' => fake()->randomElement(['team','student', 'team_student', null]),
        ];
    }
}
