<?php

namespace Tests\Unit\Policies;

use App\Helpers\PermissionType;
use App\Policies\SlotPolicy;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class SlotPolicyTest extends TestCase
{
    use CreatesEntities;

    private SlotPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new SlotPolicy;
    }

    public function test_before_grants_admins_and_operators(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);
        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);

        $this->assertTrue($this->policy->before($admin));
        $this->assertTrue($this->policy->before($operator));
    }

    public function test_before_returns_null_for_plain_user(): void
    {
        $this->assertNull($this->policy->before($this->makeUser()));
    }

    public function test_view_any_always_returns_true(): void
    {
        $this->assertTrue($this->policy->viewAny());
    }

    public function test_create_returns_false_for_non_admin(): void
    {
        $this->assertFalse($this->policy->create());
    }

    public function test_update_returns_false_for_non_admin(): void
    {
        $this->assertFalse($this->policy->update());
    }

    public function test_delete_returns_false_for_non_admin(): void
    {
        $this->assertFalse($this->policy->delete());
    }

    public function test_free_students_allows_teachers(): void
    {
        $teacher = $this->makeUser();
        $this->grant($teacher, PermissionType::Teacher->value);
        $tad = $this->makeUser();
        $this->grant($tad, PermissionType::TeacherAdmin->value);

        $this->assertTrue($this->policy->freeStudents($teacher));
        $this->assertTrue($this->policy->freeStudents($tad));
    }

    public function test_free_students_denies_plain_user(): void
    {
        $this->assertFalse($this->policy->freeStudents($this->makeUser()));
    }
}
