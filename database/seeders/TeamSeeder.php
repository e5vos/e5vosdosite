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
        $users = User::inRandomOrder()->limit($teamsize)->get();
        Team::factory()
            ->has(
                TeamMembership::factory()
                    ->count($teamsize)
                    ->state(
                        new Sequence(
                            $users->map(function ($user) {
                                return ['user_id' => $user->id];
                            })
                        )
                    )
            )
            ->count(10)
            ->create();
    }
}
