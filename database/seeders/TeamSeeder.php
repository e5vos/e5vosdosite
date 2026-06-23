<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;
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
        $userIds = User::pluck('id');

        // Each team gets up to $teamsize distinct members. Drawing from a
        // shuffled pool guarantees uniqueness within a team, so the composite
        // (user_id, team_code) primary key on team_memberships never collides.
        Team::factory()
            ->count(10)
            ->create()
            ->each(function (Team $team) use ($userIds, $teamsize) {
                $userIds->shuffle()
                    ->take($teamsize)
                    ->each(fn ($userId) => TeamMembership::factory()->create([
                        'team_code' => $team->code,
                        'user_id' => $userId,
                    ]));
            });
    }
}
