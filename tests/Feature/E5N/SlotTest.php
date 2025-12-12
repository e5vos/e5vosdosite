<?php

namespace Tests\Feature\E5N;

use App\Helpers\SlotType;
use App\Models\Permission;
use App\Models\Slot;
use App\Models\User;
use Tests\TestCase;

class SlotTest extends TestCase
{
    /**
     * test if slots can be retrieved
     */
    public function test_get_slots()
    {
        $response = $this->get('/api/slot');
        $response->assertStatus(200);
    }

    /**
     * Test if slots can be created
     */
    public function test_slot_creation()
    {
        $user = User::first();
        Permission::factory()->create(['code' => 'ADM', 'user_id' => $user->id]);
        $response = $this->actingAs($user)->postJson('api/slot', [
            'name' => 'Test slot ',
            'slot_type' => SlotType::presentation,
            'starts_at' => '2021-01-01 00:00:00',
            'ends_at' => '2021-01-01 00:00:00',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('slots', [
            'name' => 'Test slot ',
            'slot_type' => SlotType::presentation,
            'starts_at' => '2021-01-01 00:00:00',
            'ends_at' => '2021-01-01 00:00:00',
        ]);
    }

    /**
     * Test if slots can be edited
     */
    public function test_slot_edit()
    {
        $user = User::first();
        Permission::factory()->create(['code' => 'ADM', 'user_id' => $user->id]);
        $slot = Slot::first();
        $response = $this->actingAs($user)->putJson('api/slot/'.$slot->id, [
            'name' => 'Test slot '.$slot->name,
            'slot_type' => $slot->type == SlotType::presentation ? SlotType::program : SlotType::presentation,
            'starts_at' => '2021-01-01 00:00:00',
            'ends_at' => '2021-02-01 00:00:00',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('slots', [
            'name' => 'Test slot '.$slot->name,
            'slot_type' => $slot->type == SlotType::presentation ? SlotType::program : SlotType::presentation,
            'starts_at' => '2021-01-01 00:00:00',
            'ends_at' => '2021-02-01 00:00:00',
        ]);
    }

    /**
     * Test if slots can be deleted
     */
    public function test_slot_delete()
    {
        $user = User::first();
        Permission::factory()->create(['code' => 'ADM', 'user_id' => $user->id]);
        $slot = Slot::first();
        $response = $this->actingAs($user)->deleteJson('api/slot/'.$slot->id);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('slots', ['id' => $slot->id]);
    }
}
