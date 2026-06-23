<?php

namespace Tests\Feature\Auth;

use App\Helpers\PermissionType;
use App\Models\Permission;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

/**
 * Edge cases for PermissionController. Middleware is bypassed (as in the
 * existing PermissionTest) so these focus on the controller's own logic.
 */
class PermissionControllerTest extends TestCase
{
    use CreatesEntities, WithoutMiddleware;

    public function test_adding_a_duplicate_permission_conflicts()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();
        Permission::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => PermissionType::Organiser->value,
        ]);

        $this->postJson('/api/permissions', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => PermissionType::Organiser->value,
        ])->assertStatus(409);
    }

    public function test_scanner_permission_can_be_added_and_removed()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();

        $this->postJson('/api/permissions', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => PermissionType::Scanner->value,
        ])->assertStatus(201);

        $this->assertDatabaseHas('permissions', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => PermissionType::Scanner->value,
        ]);

        // Scanner removal now works (find() no longer forces the Organiser code).
        $this->deleteJson('/api/permissions', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => PermissionType::Scanner->value,
        ])->assertStatus(204);

        $this->assertDatabaseMissing('permissions', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => PermissionType::Scanner->value,
        ]);
    }

    public function test_removing_a_non_existent_permission_conflicts()
    {
        $user = $this->makeUser();
        $event = $this->makeEvent();

        // find() returns null for a missing composite key, so the controller
        // raises ResourceDidNoExistException (409).
        $this->deleteJson('/api/permissions', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => PermissionType::Organiser->value,
        ])->assertStatus(409);
    }
}
