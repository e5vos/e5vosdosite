<?php

namespace Tests\Unit\Policies;

use App\Helpers\PermissionType;
use App\Policies\SettingPolicy;
use Illuminate\Auth\Access\Response;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class SettingPolicyTest extends TestCase
{
    use CreatesEntities;

    private SettingPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new SettingPolicy;
    }

    public function test_before_allows_operators(): void
    {
        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);

        $result = $this->policy->before($operator);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertTrue($result->allowed());
    }

    public function test_before_denies_non_operators_as_not_found(): void
    {
        $user = $this->makeUser();

        $result = $this->policy->before($user);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertFalse($result->allowed());
    }

    public function test_view_any_always_returns_false(): void
    {
        $this->assertFalse($this->policy->viewAny());
    }

    public function test_set_allows_admins(): void
    {
        $admin = $this->makeUser();
        $this->grant($admin, PermissionType::Admin->value);

        $this->assertTrue($this->policy->set($admin));
    }

    public function test_set_denies_plain_user(): void
    {
        $this->assertFalse($this->policy->set($this->makeUser()));
    }

    public function test_create_always_returns_false(): void
    {
        $this->assertFalse($this->policy->create());
    }

    public function test_delete_always_returns_false(): void
    {
        $this->assertFalse($this->policy->delete());
    }
}
