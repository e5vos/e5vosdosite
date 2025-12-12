<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Setting;
use App\Models\User;
use Tests\TestCase;

class SettingTest extends TestCase
{
    /**
     * Non operators cant get settings.
     */
    public function test_non_operators_cant_get_settings()
    {
        $response = $this->getJson('/api/settings');
        $response->assertStatus(404);
    }

    /**
     * Test if settings can be retrieved by operators.
     */
    public function test_settings_can_be_retrieved_by_operator()
    {
        $user = User::first();
        Permission::factory()->create(['code' => 'OPT', 'user_id' => $user->id]);
        $response = $this->actingAs($user)->getJson('/api/setting');
        $response->assertStatus(200);
    }

    /**
     * Test if settings can be set by operators.
     */
    public function test_settings_can_be_toggled_by_operator()
    {
        $user = User::first();
        Permission::factory()->create(['code' => 'OPT', 'user_id' => $user->id]);
        Setting::firstOrCreate(['key' => 'test', 'value' => 'true']);
        $response = $this->actingAs($user)->putJson('/api/setting/test/test_value');
        $response->assertStatus(200);
        $this->assertDatabaseHas('settings', ['key' => 'test', 'value' => 'test_value']);
    }

    /**
     * Test if settings can be deleted by operators.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function test_settings_can_be_deleted_by_operator()
    {
        $user = User::first();
        Permission::factory()->create(['code' => 'OPT', 'user_id' => $user->id]);
        Setting::firstOrCreate(['key' => 'test', 'value' => 'test_value']);
        $response = $this->actingAs($user)->deleteJson('/api/setting/test');
        $response->assertStatus(200);
        $this->assertDatabaseMissing('settings', ['key' => 'test', 'value' => 'test_value']);
    }

    /**
     * Test if settings can be created by operators.
     *
     * @return void
     */
    public function test_settings_can_be_created_by_operator()
    {
        $user = User::first();
        Permission::factory()->create(['code' => 'OPT', 'user_id' => $user->id]);
        $response = $this->actingAs($user)->postJson('/api/setting', ['key' => 'test', 'value' => 'test_value']);
        $response->assertStatus(201);
        $this->assertDatabaseHas('settings', ['key' => 'test', 'value' => 'test_value']);
    }
}
