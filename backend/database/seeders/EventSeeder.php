<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Event;
use App\Models\Slot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = Location::all();
        $slots = Slot::all();

        Event::factory()
            ->count(100)
            ->create()
            ->each(
                function ($event) use ($locations, $slots) {
                    $event->location()->associate($locations->random());
                    $slot = $slots->random();
                    $event->slot()->associate($slot);
                    $slot_starts_at = Carbon::parse($slot->starts_at);
                    $slot_ends_at = Carbon::parse($slot->ends_at);
                    $slot_length = $slot_ends_at->diffInHours($slot_starts_at);
                    $event->starts_at = $slot_starts_at->addHours(rand(0, $slot_length));
                    $event->ends_at = $slot_ends_at->subHours(rand(0, $slot_length));
                    $event->signup_deadline = $event->starts_at->subDays(rand(0, 7));
                    $event->signup_type == null ? $event->capacity = null : null;
                    $event->save();
                }
            );
    }
}
