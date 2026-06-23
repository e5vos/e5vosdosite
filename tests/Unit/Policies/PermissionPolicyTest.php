<?php

namespace Tests\Unit\Policies;

use App\Helpers\PermissionType;
use App\Models\Permission;
use App\Policies\PermissionPolicy;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class PermissionPolicyTest extends TestCase
{
    use CreatesEntities;

    private PermissionPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new PermissionPolicy;
    }

    public function test_before_grants_everything_to_operators(): void
    {
        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);

        $this->assertTrue($this->policy->before($operator));
    }

    public function test_before_returns_null_for_non_operators(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);

        $this->assertNull($this->policy->before($admin));
    }

    public function test_view_any_allows_admin(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);
        $event = $this->makeEvent();

        request()->merge(['eventId' => $event->id]);

        $this->assertTrue($this->policy->viewAny($admin));
    }

    public function test_view_any_allows_teacher_admin(): void
    {
        $tad = $this->makeUser();
        $this->grant($tad, PermissionType::TeacherAdmin->value);

        $this->assertTrue($this->policy->viewAny($tad));
    }

    public function test_view_any_allows_event_organiser(): void
    {
        $event = $this->makeEvent();
        $organiser = $this->makeUser();
        $this->grant($organiser, PermissionType::Organiser->value, $event->id);

        request()->merge(['eventId' => $event->id]);

        $this->assertTrue($this->policy->viewAny($organiser));
    }

    public function test_view_any_denies_plain_user(): void
    {
        $event = $this->makeEvent();
        request()->merge(['eventId' => $event->id]);

        $this->assertFalse($this->policy->viewAny($this->makeUser()));
    }

    public function test_create_scanner_permission_allowed_for_admin(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);
        $event = $this->makeEvent();

        request()->merge(['permission' => json_encode(['code' => PermissionType::Scanner->value, 'eventId' => $event->id])]);

        $this->assertTrue($this->policy->create($admin));
    }

    public function test_create_scanner_permission_allowed_for_event_organiser(): void
    {
        $event = $this->makeEvent();
        $organiser = $this->makeUser();
        $this->grant($organiser, PermissionType::Organiser->value, $event->id);

        request()->merge(['permission' => json_encode(['code' => PermissionType::Scanner->value, 'eventId' => $event->id])]);

        $this->assertTrue($this->policy->create($organiser));
    }

    public function test_create_scanner_permission_denied_for_plain_user(): void
    {
        $event = $this->makeEvent();
        request()->merge(['permission' => json_encode(['code' => PermissionType::Scanner->value, 'eventId' => $event->id])]);

        $this->assertFalse($this->policy->create($this->makeUser()));
    }

    public function test_create_organiser_permission_allowed_only_for_admin(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);
        $event = $this->makeEvent();

        request()->merge(['permission' => json_encode(['code' => PermissionType::Organiser->value, 'eventId' => $event->id])]);

        $this->assertTrue($this->policy->create($admin));

        $organiser = $this->makeUser();
        $this->grant($organiser, PermissionType::Organiser->value, $event->id);

        $this->assertFalse($this->policy->create($organiser));
    }

    public function test_create_protected_permission_codes_always_denied(): void
    {
        $user = $this->makeUser();

        foreach ([PermissionType::Admin, PermissionType::Student, PermissionType::Teacher, PermissionType::TeacherAdmin, PermissionType::Operator] as $type) {
            request()->merge(['permission' => json_encode(['code' => $type->value, 'eventId' => null])]);
            $this->assertFalse($this->policy->create($user), "Expected false for {$type->value}");
        }
    }

    public function test_destroy_scanner_permission_allowed_for_admin(): void
    {
        $event = $this->makeEvent();
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);

        $permission = new Permission(['code' => PermissionType::Scanner->value, 'event_id' => $event->id]);

        $this->assertTrue($this->policy->destroy($admin, $permission));
    }

    public function test_destroy_organiser_permission_allowed_only_for_admin(): void
    {
        $event = $this->makeEvent();
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);
        $plain = $this->makeUser();

        $permission = new Permission(['code' => PermissionType::Organiser->value, 'event_id' => $event->id]);

        $this->assertTrue($this->policy->destroy($admin, $permission));
        $this->assertFalse($this->policy->destroy($plain, $permission));
    }

    public function test_destroy_protected_permission_codes_always_denied(): void
    {
        $user = $this->makeUser();

        foreach ([PermissionType::Admin, PermissionType::Student, PermissionType::Teacher, PermissionType::TeacherAdmin, PermissionType::Operator] as $type) {
            $permission = new Permission(['code' => $type->value]);
            $this->assertFalse($this->policy->destroy($user, $permission), "Expected false for {$type->value}");
        }
    }
}
