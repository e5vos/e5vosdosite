<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Rating;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = Event::all();
        User::all()->each(function ($user) use ($events) {
            $events->each(function ($event) use ($user) {
                if(fake()->boolean(20)){
                    Rating::factory()->create([
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                    ]);
                }

            });
        });
    }
}
