<?php

namespace Database\Seeders;

use App\Helpers\PermissionType;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Sequence;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = Event::all();
        $permissions_count = 5;
        $i = $permissions_count;
        $events_count = $events->count();
        User::factory()
        ->count(1000)
        ->has(Permission::factory()
            ->count($permissions_count)
            ->state(
                function ($attributes) use ($events, $permissions_count, &$i, $events_count) {
                    $eventId = $events[rand(0, round(($events_count)/$permissions_count)-1)+($i < $permissions_count-1 ? ++$i : $i = 0)*$events_count/$permissions_count]->id;
                    return ['event_id' => $attributes["code"] == PermissionType::Organiser->value ? $eventId : null];
                }
            )
        )
        ->create();
    }
}
