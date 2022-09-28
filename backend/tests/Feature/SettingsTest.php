<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    /**
     *  test api route for settings
     */
    public function test_settings_can_be_requested()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
        $response = $this->get('/api/settings');
        $response->assertStatus(200);
    }

    /**
     * test if settings can be updated
     */
    public function test_settings_can_be_updated()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
        $setting = Setting::get()->first();
        $response = $this->put('/api/settings',["key" => $setting->key, "value" => $setting->value]);
        $response->assertStatus(200);
    }

    /**
     * test if settings can be deleted
     */
    public function test_settings_can_be_deleted()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
        $setting = Setting::get()->first();
        $response = $this->delete('/api/settings',["key" => $setting->key]);
        $response->assertStatus(403); //noone can delete settings, only devs with db permissions from myphpadmin
    }
}
