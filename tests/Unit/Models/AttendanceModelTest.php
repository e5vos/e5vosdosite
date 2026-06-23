<?php

namespace Tests\Unit\Models;

use App\Models\Attendance;
use App\Models\TeamMemberAttendance;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class AttendanceModelTest extends TestCase
{
    use CreatesEntities;

    public function test_toggle_present_flips_and_persists()
    {
        $event = $this->makeEvent();
        $attendance = Attendance::create([
            'event_id' => $event->id,
            'user_id' => $this->makeUser()->id,
            'is_present' => false,
        ]);

        $attendance->togglePresent();
        $this->assertTrue($attendance->is_present);
        $this->assertDatabaseHas('attendances', ['id' => $attendance->id, 'is_present' => true]);

        $attendance->togglePresent();
        $this->assertFalse($attendance->fresh()->is_present);
    }

    public function test_is_present_is_cast_to_boolean()
    {
        $event = $this->makeEvent();
        $attendance = Attendance::create([
            'event_id' => $event->id,
            'user_id' => $this->makeUser()->id,
            'is_present' => 1,
        ]);

        $this->assertIsBool($attendance->fresh()->is_present);
    }

    public function test_user_and_event_relationships()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();
        $attendance = Attendance::create(['event_id' => $event->id, 'user_id' => $user->id]);

        $this->assertEquals($user->id, $attendance->user->id);
        $this->assertEquals($event->id, $attendance->event->id);
        $this->assertNull($attendance->team);
    }

    public function test_team_relationship()
    {
        $team = $this->makeTeam();
        $event = $this->makeEvent(['signup_type' => 'team']);
        $attendance = Attendance::create(['event_id' => $event->id, 'team_code' => $team->code]);

        $this->assertEquals($team->code, $attendance->team->code);
    }

    public function test_team_member_attendances_relationship()
    {
        $team = $this->makeTeam();
        $member = $this->makeUser();
        $this->addMember($team, $member);
        $event = $this->makeEvent(['signup_type' => 'team']);
        $attendance = Attendance::create(['event_id' => $event->id, 'team_code' => $team->code]);

        TeamMemberAttendance::create([
            'attendance_id' => $attendance->id,
            'user_id' => $member->id,
            'is_present' => true,
        ]);

        $this->assertCount(1, $attendance->teamMemberAttendances);
        $this->assertEquals($member->id, $attendance->teamMemberAttendances->first()->user->id);
    }

    public function test_team_member_attendance_toggle_present()
    {
        $team = $this->makeTeam();
        $member = $this->makeUser();
        $this->addMember($team, $member);
        $event = $this->makeEvent(['signup_type' => 'team']);
        $attendance = Attendance::create(['event_id' => $event->id, 'team_code' => $team->code]);
        $tma = TeamMemberAttendance::create([
            'attendance_id' => $attendance->id,
            'user_id' => $member->id,
            'is_present' => false,
        ]);

        $tma->togglePresent();
        $this->assertTrue($tma->is_present);
        $this->assertEquals($attendance->id, $tma->attendance->id);
    }
}
