<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teamsize = 10;
        $users = User::inRandomOrder()->limit($teamsize*10)->get();
        Team::factory()
            ->has(
                TeamMembership::factory()
                ->count($teamsize)
                ->sequence(fn ($sequence) => ['user_id' => $users[$sequence->index]->id]),
                'members'
            )
            ->count(10)
            ->create();
    }
}
