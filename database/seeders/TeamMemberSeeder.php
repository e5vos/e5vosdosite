<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\TeamMember;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TeamMember::factory(15)->create();
    }
}
