<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Team;
use App\Models\TeamMemberAttendance;
use App\Models\User;
use Illuminate\Database\Seeder;

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
        $users = User::all();
        $teams = Team::all();
        foreach ($events as &$event) {
            $attendance_count = rand(0, $event->capacity ?? 30);
            if (str_contains($event->signup_type, 'user')) {
                $current_users = $users->random(min($attendance_count / 2, count($users)));
                Attendance::factory()
                    ->count(count($current_users))
                    ->sequence(
                        fn ($sequence) => [
                            'user_id' => $current_users[$sequence->index]->id,
                            'event_id' => $event->id,
                        ]
                    )
                    ->create();
            }
            if (str_contains($event->signup_type, 'team')) {
                $current_teams = $teams->random(min($attendance_count / 2, count($teams)));
                Attendance::factory()
                    ->count(count($current_teams))
                    ->sequence(
                        fn ($sequence) => [
                            'team_code' => $current_teams[$sequence->index]->code,
                            'event_id' => $event->id,
                        ]
                    )
                    ->create();
                $teamAttendances = Attendance::where('event_id', $event->id)->where('team_code', '!=', null)->get();
                foreach ($teamAttendances as &$teamAttendance) {
                    $team_members = $teamAttendance->team->members()->get();
                    $attendedSize = rand(1, $team_members->count());
                    $members_attending = $team_members->random(min($attendedSize, $team_members->count()));
                    TeamMemberAttendance::factory()
                        ->count(count($members_attending))
                        ->sequence(
                            fn ($sequence) => [
                                'user_id' => $members_attending[$sequence->index]->id,
                                'attendance_id' => $teamAttendance->id,
                            ]
                        )->create();
                }
            }
        }
    }
}
