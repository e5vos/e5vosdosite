<?php

namespace Tests\Feature;

use App\Helpers\PermissionType;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    /**
     * test if a user can be added as an organiser to an event
     */
    public function testAddPermissionEvent()
    {
        Permission::where('user_id', 1)->delete();
        $response = $this->post('/api/permissions/1/event/1');
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
    public function testRemovePermissionEvent()
    {
        Permission::where('user_id', 1)->delete();
        Permission::create([
            'user_id' => 1,
            'event_id' => 1,
            'code' => PermissionType::Organiser->value,
        ]);
        $response = $this->delete('/api/permissions/1/event/1');
        $response->assertStatus(200);
        $this->assertDatabaseMissing('permissions', [
            'user_id' => 1,
            'event_id' => 1,
            'code' => PermissionType::Organiser->value,
        ]);
    }
}
