<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{
    Rating,
    Event
};

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Event::all() as $event){
                Rating::factory(10)->create(['event_id' => $event->id]);
        }
    }
}
