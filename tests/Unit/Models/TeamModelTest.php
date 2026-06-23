<?php

namespace Tests\Unit\Models;

use App\Exceptions\AlreadySignedUpException;
use App\Exceptions\EventFullException;
use App\Helpers\MembershipType;
use App\Models\Attendance;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class TeamModelTest extends TestCase
{
    use CreatesEntities;

    public function test_add_member_creates_membership()
    {
        $team = $this->makeTeam();
        $user = $this->makeUser();

        $membership = $team->addMember($user, MembershipType::Leader);

        $this->assertSame(MembershipType::Leader, $membership->role);
        $this->assertDatabaseHas('team_memberships', [
            'team_code' => $team->code,
            'user_id' => $user->id,
        ]);
        $this->assertTrue($team->members()->get()->contains('id', $user->id));
    }

    public function test_members_and_memberships_relationships()
    {
        $team = $this->makeTeam();
        $a = $this->makeUser();
        $b = $this->makeUser();
        $this->addMember($team, $a, MembershipType::Leader);
        $this->addMember($team, $b, MembershipType::Member);

        $this->assertCount(2, $team->members()->get());
        $this->assertCount(2, $team->memberships()->get());
    }

    public function test_team_sign_up_creates_attendance_and_member_attendances()
    {
        $team = $this->makeTeam();
        $a = $this->makeUser();
        $b = $this->makeUser();
        $this->addMember($team, $a, MembershipType::Leader);
        $this->addMember($team, $b, MembershipType::Member);

        $event = $this->makeEvent(['signup_type' => 'team']);
        $attendance = $team->signUp($event);

        $this->assertInstanceOf(Attendance::class, $attendance);
        $this->assertDatabaseHas('attendances', [
            'event_id' => $event->id,
            'team_code' => $team->code,
        ]);
        // One team_member_attendance row per team member.
        $this->assertSame(2, $attendance->teamMemberAttendances()->count());
    }

    public function test_team_sign_up_twice_throws()
    {
        $team = $this->makeTeam();
        $this->addMember($team, $this->makeUser(), MembershipType::Leader);
        $event = $this->makeEvent(['signup_type' => 'team']);

        $team->signUp($event);
        $this->expectException(AlreadySignedUpException::class);
        $team->signUp($event);
    }

    public function test_team_sign_up_to_full_event_throws()
    {
        $event = $this->makeEvent(['signup_type' => 'team_user', 'capacity' => 1]);
        // Occupy the single slot with an unrelated attendance.
        Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        $team = $this->makeTeam();
        $this->addMember($team, $this->makeUser(), MembershipType::Leader);

        $this->expectException(EventFullException::class);
        $team->signUp($event);
    }

    public function test_team_attend_marks_present()
    {
        $team = $this->makeTeam();
        $this->addMember($team, $this->makeUser(), MembershipType::Leader);
        $event = $this->makeEvent(['signup_type' => 'team']);

        $attendance = $team->attend($event);
        $this->assertTrue($attendance->is_present);
        $this->assertDatabaseHas('attendances', [
            'event_id' => $event->id,
            'team_code' => $team->code,
            'is_present' => true,
        ]);
    }

    public function test_team_events_relationship()
    {
        $team = $this->makeTeam();
        $this->addMember($team, $this->makeUser(), MembershipType::Leader);
        $event = $this->makeEvent(['signup_type' => 'team']);
        $team->signUp($event);

        $this->assertTrue($team->events()->get()->contains('id', $event->id));
    }
}
