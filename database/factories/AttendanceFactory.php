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

    public function configure(){
        return $this->afterMaking(function(Attendance $attendance){
            $randomEvent = Event::inRandomOrder()->first();;
            $attendance->event()->associate($randomEvent);
        });
    }

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
