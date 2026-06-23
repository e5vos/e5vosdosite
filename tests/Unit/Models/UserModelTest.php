<?php

namespace Tests\Unit\Models;

use App\Exceptions\AlreadySignedUpException;
use App\Exceptions\StudentBusyException;
use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use App\Models\Attendance;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use CreatesEntities;

    public function test_has_permission_returns_true_for_granted_code()
    {
        $user = $this->makeUser();
        $this->grant($user, PermissionType::Teacher->value);

        $this->assertTrue($user->hasPermission(PermissionType::Teacher->value));
        $this->assertFalse($user->hasPermission(PermissionType::Admin->value));
    }

    public function test_operator_has_every_permission()
    {
        $user = $this->makeUser();
        $this->grant($user, PermissionType::Operator->value);

        // Operator short-circuits hasPermission for any code.
        $this->assertTrue($user->hasPermission(PermissionType::Admin->value));
        $this->assertTrue($user->hasPermission(PermissionType::Teacher->value));
        $this->assertTrue($user->hasPermission(PermissionType::Scanner->value));
    }

    public function test_organises_and_scans_event()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();
        $this->grant($user, PermissionType::Organiser->value, $event->id);

        $this->assertTrue($user->organisesEvent($event->id));
        $this->assertFalse($user->scansEvent($event->id));
        $this->assertTrue($user->organisedEvents->contains('id', $event->id));

        $scanned = $this->makeEvent();
        $this->grant($user, PermissionType::Scanner->value, $scanned->id);
        $this->assertTrue($user->scansEvent($scanned->id));
    }

    public function test_team_membership_helpers()
    {
        $user = $this->makeUser();
        $team = $this->makeTeam();

        $this->assertFalse($user->isInTeam($team));
        $this->assertFalse($user->isLeaderOfTeam($team));

        $this->addMember($team, $user, MembershipType::Member);
        $this->assertTrue($user->isInTeam($team));
        $this->assertTrue($user->isInTeam($team->code));
        $this->assertFalse($user->isLeaderOfTeam($team));
        $this->assertTrue($user->teams->contains('code', $team->code));
    }

    public function test_invited_member_is_not_considered_in_team()
    {
        $user = $this->makeUser();
        $team = $this->makeTeam();
        $this->addMember($team, $user, MembershipType::Invited);

        // isInTeam explicitly excludes the "invited" role.
        $this->assertFalse($user->isInTeam($team));
    }

    public function test_leader_helper()
    {
        $user = $this->makeUser();
        $team = $this->makeTeam();
        $this->addMember($team, $user, MembershipType::Leader);

        $this->assertTrue($user->isLeaderOfTeam($team));
        $this->assertTrue($user->isInTeam($team));
    }

    public function test_sign_up_creates_attendance()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();

        $this->actingAs($user);
        $attendance = $user->signUp($event);

        $this->assertInstanceOf(Attendance::class, $attendance);
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $this->assertTrue($user->events->contains('id', $event->id));
    }

    public function test_sign_up_twice_throws()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();
        $this->actingAs($user);
        $user->signUp($event);

        $this->expectException(AlreadySignedUpException::class);
        $user->signUp($event);
    }

    public function test_sign_up_busy_in_presentation_slot_throws()
    {
        $slot = $this->makeSlot(SlotType::presentation);
        $first = $this->makeEvent(['signup_type' => 'user'], $slot);
        $second = $this->makeEvent(['signup_type' => 'user'], $slot);

        $user = $this->makeUser();
        $this->actingAs($user);
        $user->signUp($first);

        // A student already booked in a presentation slot can't take a second
        // presentation in the same slot.
        $this->expectException(StudentBusyException::class);
        $user->signUp($second);
    }

    public function test_is_busy_in_slot()
    {
        $slot = $this->makeSlot(SlotType::program);
        $event = $this->makeEvent([], $slot);
        $user = $this->makeUser();
        $this->actingAs($user);

        $this->assertFalse($user->isBusyInSlot($slot));
        $user->signUp($event);
        $this->assertTrue($user->isBusyInSlot($slot));
        $this->assertTrue($user->isBusyInSlot($slot->id));
    }

    public function test_is_busy_for_current_event()
    {
        $event = $this->makeEvent();
        $user = $this->makeUser();
        $this->actingAs($user);

        $this->assertFalse($user->isBusy());
        $user->signUp($event);
        $this->assertTrue($user->isBusy());
    }

    public function test_attend_creates_and_toggles_presence()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();
        $this->actingAs($user);

        $attendance = $user->attend($event);
        $this->assertTrue($attendance->is_present);
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'is_present' => true,
        ]);

        // Attending again toggles presence back off.
        $attendance = $user->attend($event);
        $this->assertFalse($attendance->is_present);
    }

    public function test_signup_and_attendance_relationships()
    {
        $user = $this->makeUser();
        $present = $this->makeEvent();
        $absent = $this->makeEvent();
        Attendance::create(['event_id' => $present->id, 'user_id' => $user->id, 'is_present' => true]);
        Attendance::create(['event_id' => $absent->id, 'user_id' => $user->id, 'is_present' => false]);

        // signups() = all attendances; attendances() = only where present.
        $this->assertCount(2, $user->signups);
        $this->assertCount(1, $user->attendances);
        $this->assertTrue($user->attendances->contains('event_id', $present->id));
    }

    public function test_team_signups_and_activity_relationships()
    {
        $user = $this->makeUser();
        $team = $this->makeTeam();
        $this->addMember($team, $user, MembershipType::Leader);
        $event = $this->makeEvent(['signup_type' => 'team']);
        $this->actingAs($user);
        $team->signUp($event);

        // The user is linked to the team attendance via team_member_attendances.
        $this->assertTrue($user->teamSignups->contains('event_id', $event->id));
        $this->assertNotNull($user->userActivity);
        $this->assertNotNull($user->teamActivity);
        // ratings() relationship is queryable even when empty.
        $this->assertCount(0, $user->ratings);
    }

    public function test_presentations_returns_only_presentation_slot_events()
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $presentationEvent = $this->makeEvent([], $this->makeSlot(SlotType::presentation));
        $programEvent = $this->makeEvent([], $this->makeSlot(SlotType::program));
        $user->signUp($presentationEvent);
        $user->signUp($programEvent);

        $ids = $user->presentations()->pluck('events.id')->all();
        $this->assertContains($presentationEvent->id, $ids);
        $this->assertNotContains($programEvent->id, $ids);
    }
}
