<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Team;
use Database\Factories\TeamMemeberAttendanceFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = Event::all();
        $users = user::all();
        $teams = Team::all();
        foreach ($events as $event) {
            $individualAttendanceCount = 10;
            Attendance::factory()
                ->count($individualAttendanceCount)
                ->state(
                    new Sequence(
                        $users
                            ->random($individualAttendanceCount)
                            ->map(
                                fn($user) => ['user_id' => $user->id, 'event_id' => $event->id]
                            )
                        )
                    )
                ->create();
            $teamAttendanceCount = 10;
            $teamAttendances = Attendance::factory()
                ->count($teamAttendanceCount)
                ->state(
                    new Sequence(
                        $teams
                            ->random($teamAttendanceCount)
                            ->map(
                                fn($team) => ['team_id' => $team->id, 'event_id' => $event->id]
                            )
                        )
                    )
                ->create();
            foreach($teamAttendances as &$teamAttendance){
                $team = $teamAttendance->team();
                $attendedSize = rand(1, $team->members()->count());
                $members = $team->members()->get()->random($attendedSize);
                Attendance::factory()
                    ->count($attendedSize)
                    ->state(
                        new Sequence(
                            $members
                                ->map(
                                    fn($member) => ['user_id' => $member->id, 'event_id' => $event->id]
                                )
                            )
                        )
                    ->create();
            }
        }
    }
}
