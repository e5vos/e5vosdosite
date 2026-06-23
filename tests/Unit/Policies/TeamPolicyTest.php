<?php

namespace Tests\Unit\Policies;

use App\Helpers\PermissionType;
use App\Policies\TeamPolicy;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class TeamPolicyTest extends TestCase
{
    use CreatesEntities;

    private TeamPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TeamPolicy;
    }

    public function test_force_delete_always_returns_false(): void
    {
        $this->assertFalse($this->policy->forceDelete());
    }

    public function test_create_always_returns_true(): void
    {
        $this->assertTrue($this->policy->create());
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

    public function test_view_any_allows_teachers(): void
    {
        $teacher = $this->makeUser();
        $this->grant($teacher, PermissionType::Teacher->value);
        $tad = $this->makeUser();
        $this->grant($tad, PermissionType::TeacherAdmin->value);

        $this->assertTrue($this->policy->viewAny($teacher));
        $this->assertTrue($this->policy->viewAny($tad));
    }

    public function test_view_any_denies_plain_user(): void
    {
        $this->assertFalse($this->policy->viewAny($this->makeUser()));
    }
}
