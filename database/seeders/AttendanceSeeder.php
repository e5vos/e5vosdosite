<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Team;
use Database\Factories\TeamMemeberAttendanceFactory;

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
            $event->attendances()->saveMany(
                Attendance::factory()->count($event->capacity/2 ?? 10)->create()->each(function ($attendance) use ($event, $users, $teams) {
                    $attendance->event_id = $event->id;
                    $user = fake()->randomElement($users);
                    $team = fake()->randomElement($teams);
                    $team_members = $team->members()->toArray();
                    $willattend = rand(0, $team->members->count());
                    switch($event->signup_type){
                        case 'user':
                            $attendance->user_id = $user->id;
                            break;
                        case 'team':
                            $attendance->team_id = $team->id;
                            TeamMemeberAttendanceFactory::new()->count($willattend)->create(
                                function ($team_member_attendance) use ($event) {
                                    $team_member_attendance->attendance_id = $event->attendance;
                                }
                            )->each(
                                function ($team_member_attendance) use ($team_members) {
                                    $team_member_attendance->user_id = $team_members[0]->id;
                                    array_shift($team_members);
                                }
                            );
                            break;
                        case 'team_user':
                            if (fake()->boolean()){
                                $attendance->user_id = $user->id;

                            }else {
                                $attendance->team_id = $team->id;
                                TeamMemeberAttendanceFactory::new()->count($willattend)->create(
                                    function ($team_member_attendance) use ($event) {
                                        $team_member_attendance->attendance_id = $event->attendance;
                                    }
                                )->each(
                                    function ($team_member_attendance) use ($team_members) {
                                        $team_member_attendance->user_id = $team_members[0]->id;
                                        array_shift($team_members);
                                    }
                                );
                            }
                            break;
                        default:
                            break;
                    }
                })
            );
        }
    }
}
