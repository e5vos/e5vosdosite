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
        $startsAt = fake()->dateTimeBetween('now', '+3 days');
        return [
            'name' => fake()->word(),
            'description' => fake()->paragraph(),
            'starts_at' => $startsAt,
            'ends_at' => fake()->dateTimeBetween($startsAt, '+3 days'),
            'organiser' => fake()->word(),
            'signup_deadline' => fake()->dateTimeBetween('-3 days', $startsAt),
            'capacity' =>  fake()->boolean() ? null : rand(1, 30),
            'slot_id' => null,
            'img_url' => rand(0, 10) > 7 ? fake()->imageUrl() : null,
            'signup_type' => fake()->randomElement(['team', 'user', 'team_user', null]),
        ];
    }
}
