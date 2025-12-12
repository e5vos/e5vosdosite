<?php

namespace Tests\Feature;

use App\Helpers\PermissionType;
use App\Models\Permission;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * test if a user can be added as an organiser to an event
     */
    public function test_add_permission_event()
    {
        Permission::where('user_id', 1)->delete();
        $response = $this->post('/api/permissions/1/event/1', ['Accept' => 'application/json']);
        $response->assertStatus(201);
        $this->assertDatabaseHas('permissions', [
            'user_id' => 1,
            'event_id' => 1,
            'code' => PermissionType::Organiser->value,
        ]);
    }

    /**
     * test if a user can be removed as an organiser from an event
     */
    public function test_remove_permission_event()
    {
        Permission::where('user_id', 1)->delete();
        Permission::create([
            'user_id' => 1,
            'event_id' => 1,
            'code' => PermissionType::Organiser->value,
        ]);
        $response = $this->delete('/api/permissions/1/event/1', ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('permissions', [
            'user_id' => 1,
            'event_id' => 1,
            'code' => PermissionType::Organiser->value,
        ]);
    }
}
