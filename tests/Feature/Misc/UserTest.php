<?php

namespace Tests\Feature\Misc;

use App\Helpers\PermissionType;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class UserTest extends TestCase
{
    use CreatesEntities;

    public function test_users_index_requires_authentication()
    {
        $this->getJson('/api/users')->assertStatus(401);
    }

    public function test_users_index_search_returns_matches()
    {
        $this->makeUser(['name' => 'Z%searchable Coverage Person']);
        $actor = $this->makeUser();

        $this->actingAs($actor)
            ->getJson('/api/users?q=searchable Coverage Person')
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Z%searchable Coverage Person']);
    }

    public function test_users_index_with_sentinel_query_returns_empty_list()
    {
        $actor = $this->makeUser();

        $this->actingAs($actor)
            ->getJson('/api/users?q=-1')
            ->assertStatus(200)
            ->assertExactJson([]);
    }

    public function test_current_user_endpoint_returns_self()
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->getJson('/api/user/currentuser')
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id]);
    }

    public function test_show_self_without_id()
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->getJson('/api/user')
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id]);
    }

    public function test_viewing_another_user_requires_privilege()
    {
        $target = $this->makeUser();

        $this->actingAs($this->makeUser())
            ->getJson('/api/user/'.$target->id)
            ->assertStatus(403);

        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);
        $this->actingAs($operator)
            ->getJson('/api/user/'.$target->id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $target->id]);
    }

    public function test_user_can_update_themselves()
    {
        $user = $this->makeUser(['name' => 'Before']);

        $this->actingAs($user)
            ->putJson('/api/user/'.$user->id, ['name' => 'After'])
            ->assertStatus(200);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'After']);
    }

    public function test_user_cannot_update_another_user()
    {
        $target = $this->makeUser(['name' => 'Untouched']);

        $this->actingAs($this->makeUser())
            ->putJson('/api/user/'.$target->id, ['name' => 'Hacked'])
            ->assertStatus(403);

        $this->assertDatabaseHas('users', ['id' => $target->id, 'name' => 'Untouched']);
    }

    public function test_my_teams_listing()
    {
        $user = $this->makeUser();
        $team = $this->makeTeam();
        $this->addMember($team, $user);

        $this->actingAs($user)
            ->getJson('/api/user/currentuser/teams')
            ->assertStatus(200)
            ->assertJsonFragment(['code' => $team->code]);
    }
}
