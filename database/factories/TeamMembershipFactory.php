<?php

namespace Database\Factories;

use App\Helpers\MembershipType;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamMembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'role' => fake()->randomElement(array_column(MembershipType::cases(), 'value')),
        ];
    }
}
