<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MembershipType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'role' => $this->fake()->randomElement(MembershipType::cases()),
        ];
    }
}
