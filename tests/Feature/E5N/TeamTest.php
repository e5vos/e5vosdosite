<?php

namespace Tests\Feature\E5N;

use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use CreatesEntities;

    public function test_index_requires_privileged_role()
    {
        $user = $this->makeUser();
        $this->actingAs($user)->getJson('/api/team')->assertStatus(403);

        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);
        $this->actingAs($operator)->getJson('/api/team')->assertStatus(200);
    }

    public function test_create_team_makes_creator_the_leader()
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->postJson('/api/team', [
            'code' => 'ALPHATEAM',
            'name' => 'Alpha Team',
        ]);

        // A freshly created model returned as a JsonResource responds 201.
        $response->assertStatus(201);
        $this->assertDatabaseHas('teams', ['code' => 'ALPHATEAM', 'name' => 'Alpha Team']);
        $this->assertDatabaseHas('team_memberships', [
            'team_code' => 'ALPHATEAM',
            'user_id' => $user->id,
            'role' => MembershipType::Leader->value,
        ]);
    }

    public function test_create_team_with_existing_code_conflicts()
    {
        $existing = $this->makeTeam(['code' => 'DUPLICATED']);
        $user = $this->makeUser();

        $this->actingAs($user)
            ->postJson('/api/team', ['code' => 'DUPLICATED', 'name' => 'Another'])
            ->assertStatus(409);
    }

    public function test_member_can_view_team_but_outsider_cannot()
    {
        $team = $this->makeTeam();
        $member = $this->makeUser();
        $this->addMember($team, $member, MembershipType::Member);

        $this->actingAs($member)->getJson('/api/team/'.$team->code)->assertStatus(200);

        $outsider = $this->makeUser();
        $this->actingAs($outsider)->getJson('/api/team/'.$team->code)->assertStatus(403);
    }

    public function test_teacher_can_view_any_team()
    {
        $team = $this->makeTeam();
        $teacher = $this->makeUser();
        $this->grant($teacher, PermissionType::Teacher->value);

        $this->actingAs($teacher)->getJson('/api/team/'.$team->code)->assertStatus(200);
    }

    public function test_leader_can_delete_team()
    {
        $team = $this->makeTeam();
        $leader = $this->makeUser();
        $this->addMember($team, $leader, MembershipType::Leader);

        $this->actingAs($leader)->deleteJson('/api/team/'.$team->code)->assertStatus(204);
        $this->assertSoftDeleted('teams', ['code' => $team->code]);
    }

    public function test_non_leader_cannot_delete_team()
    {
        $team = $this->makeTeam();
        $member = $this->makeUser();
        $this->addMember($team, $member, MembershipType::Member);

        $this->actingAs($member)->deleteJson('/api/team/'.$team->code)->assertStatus(403);
        $this->assertDatabaseHas('teams', ['code' => $team->code, 'deleted_at' => null]);
    }

    public function test_leader_can_restore_team()
    {
        $team = $this->makeTeam();
        $leader = $this->makeUser();
        $this->addMember($team, $leader, MembershipType::Leader);
        $team->delete();

        $this->actingAs($leader)->putJson('/api/team/'.$team->code.'/restore')->assertStatus(200);
        $this->assertDatabaseHas('teams', ['code' => $team->code, 'deleted_at' => null]);
    }

    public function test_non_leader_cannot_update_team()
    {
        $team = $this->makeTeam();
        $member = $this->makeUser();
        $this->addMember($team, $member, MembershipType::Member);

        // The update action is guarded by the "isLeaderOfTeam" policy; a plain
        // member is rejected before reaching the controller.
        $this->actingAs($member)
            ->putJson('/api/team/'.$team->code, ['name' => 'Renamed'])
            ->assertStatus(403);
    }

    public function test_leader_can_update_team_name()
    {
        $team = $this->makeTeam(['name' => 'Old Name']);
        $leader = $this->makeUser();
        $this->addMember($team, $leader, MembershipType::Leader);

        $this->actingAs($leader)
            ->putJson('/api/team/'.$team->code, ['name' => 'New Name'])
            ->assertStatus(200);

        $this->assertDatabaseHas('teams', ['code' => $team->code, 'name' => 'New Name']);
    }

    public function test_updating_team_code_to_an_existing_code_conflicts()
    {
        $team = $this->makeTeam();
        $other = $this->makeTeam();
        $leader = $this->makeUser();
        $this->addMember($team, $leader, MembershipType::Leader);

        $this->actingAs($leader)
            ->putJson('/api/team/'.$team->code, ['code' => $other->code, 'name' => 'X'])
            ->assertStatus(409);
    }

    public function test_leader_can_invite_a_new_member()
    {
        $team = $this->makeTeam();
        $leader = $this->makeUser();
        $this->addMember($team, $leader, MembershipType::Leader);
        $invitee = $this->makeUser();

        $response = $this->actingAs($leader)->putJson('/api/team/'.$team->code.'/members', [
            'userId' => $invitee->id,
            'promote' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('team_memberships', [
            'team_code' => $team->code,
            'user_id' => $invitee->id,
            'role' => MembershipType::Invited->value,
        ]);
    }

    public function test_leader_can_promote_member_to_leader()
    {
        $team = $this->makeTeam();
        $leader = $this->makeUser();
        $member = $this->makeUser();
        $this->addMember($team, $leader, MembershipType::Leader);
        $this->addMember($team, $member, MembershipType::Member);

        $response = $this->actingAs($leader)->putJson('/api/team/'.$team->code.'/members', [
            'userId' => $member->id,
            'promote' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('team_memberships', [
            'team_code' => $team->code,
            'user_id' => $member->id,
            'role' => MembershipType::Leader->value,
        ]);
    }

    public function test_leader_can_kick_member()
    {
        $team = $this->makeTeam();
        $leader = $this->makeUser();
        $member = $this->makeUser();
        $this->addMember($team, $leader, MembershipType::Leader);
        $this->addMember($team, $member, MembershipType::Member);

        $response = $this->actingAs($leader)->putJson('/api/team/'.$team->code.'/members', [
            'userId' => $member->id,
            'promote' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('team_memberships', [
            'team_code' => $team->code,
            'user_id' => $member->id,
        ]);
    }

    public function test_promote_requires_parameters()
    {
        $team = $this->makeTeam();
        $leader = $this->makeUser();
        $this->addMember($team, $leader, MembershipType::Leader);

        // The policy aborts with 400 when userId/promote are missing.
        $this->actingAs($leader)
            ->putJson('/api/team/'.$team->code.'/members', [])
            ->assertStatus(400);
    }
}
