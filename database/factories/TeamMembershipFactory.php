<?php

namespace Database\Factories;

use App\Helpers\MembershipType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
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
