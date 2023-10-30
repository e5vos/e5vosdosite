<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $present = fake()->boolean();

        return [
            'is_present' => $present,
            'rank' => $present ? fake()->numberBetween(1, 5) : null,
        ];
    }
}
