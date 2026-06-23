<?php

namespace Tests\Unit\Policies;

use App\Helpers\PermissionType;
use App\Policies\UserPolicy;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use CreatesEntities;

    private UserPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new UserPolicy;
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

    public function test_view_allows_teachers_and_teacher_admins(): void
    {
        $teacher = $this->makeUser();
        $this->grant($teacher, PermissionType::Teacher->value);
        $tad = $this->makeUser();
        $this->grant($tad, PermissionType::TeacherAdmin->value);

        $this->assertTrue($this->policy->view($teacher));
        $this->assertTrue($this->policy->view($tad));
    }

    public function test_view_denies_plain_user(): void
    {
        $this->assertFalse($this->policy->view($this->makeUser()));
    }

    public function test_view_any_always_returns_true(): void
    {
        $this->assertTrue($this->policy->viewAny());
    }

    public function test_create_always_returns_false(): void
    {
        $this->assertFalse($this->policy->create());
    }

    public function test_update_allows_self(): void
    {
        $user = $this->makeUser();
        request()->merge(['userId' => $user->id]);

        $this->assertTrue($this->policy->update($user));
    }

    public function test_update_denies_other_user(): void
    {
        $user = $this->makeUser();
        $other = $this->makeUser();
        request()->merge(['userId' => $other->id]);

        $this->assertFalse($this->policy->update($user));
    }

    public function test_update_with_explicit_model(): void
    {
        $user = $this->makeUser();

        $this->assertTrue($this->policy->update($user, $user));

        $other = $this->makeUser();
        $this->assertFalse($this->policy->update($user, $other));
    }

    public function test_delete_always_returns_false(): void
    {
        $this->assertFalse($this->policy->delete());
    }

    public function test_restore_always_returns_false(): void
    {
        $this->assertFalse($this->policy->restore());
    }

    public function test_search_always_returns_true(): void
    {
        $this->assertTrue($this->policy->search());
    }
}
