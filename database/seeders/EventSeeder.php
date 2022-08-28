<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Event;
use App\Models\Slot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        $events = Event::factory()
            ->count(100)
            ->create();
        foreach ($events as $event) {
            $event->location()->associate($locations->random());
            if (rand(0, 10) > 8) {
                $event->slot()->associate($slots->random());
            }
        };
    }
}
