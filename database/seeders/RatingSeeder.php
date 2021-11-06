<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Rating;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Event::all() as $event){
                Rating::factory(10)->create(['event_id' => $event->id]);
        }
    }
}
