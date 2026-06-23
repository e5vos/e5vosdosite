<?php

namespace Tests\Unit\Policies;

use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
use App\Policies\TeamMembershipPolicy;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class TeamMembershipPolicyTest extends TestCase
{
    use CreatesEntities;

    private TeamMembershipPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TeamMembershipPolicy;
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

    public function test_view_allows_team_member(): void
    {
        $team = $this->makeTeam();
        $member = $this->makeUser();
        $this->addMember($team, $member);

        // Pass the team code as string (the policy supports Team|string|null)
        $this->assertTrue($this->policy->view($member, $team->code));
    }

    public function test_view_denies_outsider(): void
    {
        $team = $this->makeTeam();
        $outsider = $this->makeUser();

        $this->assertFalse($this->policy->view($outsider, $team->code));
    }

    public function test_create_allows_team_leader(): void
    {
        $team = $this->makeTeam();
        $leader = $this->makeUser();
        $this->addMember($team, $leader, MembershipType::Leader);

        $this->assertTrue($this->policy->create($leader, $team->code));
    }

    public function test_create_denies_plain_member(): void
    {
        $team = $this->makeTeam();
        $member = $this->makeUser();
        $this->addMember($team, $member, MembershipType::Member);

        $this->assertFalse($this->policy->create($member, $team->code));
    }

    public function test_view_with_team_model_object(): void
    {
        $team = $this->makeTeam();
        $member = $this->makeUser();
        $this->addMember($team, $member);

        $this->assertTrue($this->policy->view($member, $team));
    }
}
