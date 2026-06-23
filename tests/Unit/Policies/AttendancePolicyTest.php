<?php

namespace Tests\Unit\Policies;

use App\Helpers\PermissionType;
use App\Models\Attendance;
use App\Policies\AttendancePolicy;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

/**
 * AttendancePolicy's view/create/delete abilities aren't reachable through the
 * tested routes, so they're exercised directly here.
 */
class AttendancePolicyTest extends TestCase
{
    use CreatesEntities;

    private AttendancePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new AttendancePolicy;
    }

    public function test_before_grants_everything_to_operators_and_admins()
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);
        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);
        $plain = $this->makeUser();

        $this->assertTrue($this->policy->before($admin));
        $this->assertTrue($this->policy->before($operator));
        $this->assertNull($this->policy->before($plain));
    }

    public function test_view_any_requires_teacher_role()
    {
        $teacher = $this->makeUser();
        $this->grant($teacher, PermissionType::Teacher->value);

        $this->assertTrue($this->policy->viewAny($teacher));
        $this->assertFalse($this->policy->viewAny($this->makeUser()));
    }

    public function test_event_organiser_can_view_create_and_delete_attendances()
    {
        $event = $this->makeEvent();
        $organiser = $this->makeUser();
        $this->grant($organiser, PermissionType::Organiser->value, $event->id);
        $attendance = Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        $this->assertTrue($this->policy->view($organiser, $attendance));
        $this->assertTrue($this->policy->create($organiser, $event->id));
        $this->assertTrue($this->policy->delete($organiser, $attendance));
    }

    public function test_outsider_cannot_view_create_or_delete_attendances()
    {
        $event = $this->makeEvent();
        $outsider = $this->makeUser();
        $attendance = Attendance::create(['event_id' => $event->id, 'user_id' => $this->makeUser()->id]);

        $this->assertFalse($this->policy->view($outsider, $attendance));
        $this->assertFalse($this->policy->create($outsider, $event->id));
        $this->assertFalse($this->policy->delete($outsider, $attendance));
    }

    public function test_update_and_restore_are_disabled()
    {
        $this->assertFalse($this->policy->update());
        $this->assertFalse($this->policy->restore());
        $this->assertFalse($this->policy->forceDelete());
    }
}
