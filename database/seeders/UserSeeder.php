<?php

namespace Database\Seeders;

use App\Helpers\PermissionType;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1000)->has(Permission::factory(2)->each(
            function ($permission) {
                $permission->code == PermissionType::Organiser ? $permission->event_id = Event::inRandomOrder()->first()->id : $permission->event_id = null;
            }
        ))->create();
    }
}
