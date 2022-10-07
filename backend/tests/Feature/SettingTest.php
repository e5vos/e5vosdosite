<?php

namespace Tests\Feature;

use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class SettingTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Non operators cant get settings.
     */
    public function testNonOperatorsCantGetSettings()
    {
        $response = $this->getJson('/api/settings');
        $response->assertStatus(404);
    }

    /**
     * Test if settings can be retrieved.
     */
    public function testSettingsCanBeRetrieved()
    {
        $user = User::first();
        Permission::factory()->create(['code' => 'OPT', 'user_id' => $user->id]);
        $response = $this->actingAs($user)->getJson('/api/setting');
        $response->assertStatus(200);
    }

}
