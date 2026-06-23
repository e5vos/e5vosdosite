<?php

namespace Tests\Feature\BaseSiteFeatures;

use App\Helpers\PermissionType;
use App\Models\Location;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class LocationTest extends TestCase
{
    use CreatesEntities;

    public function test_locations_index_is_public()
    {
        Location::factory()->create();

        $this->getJson('/api/locations')->assertStatus(200);
    }

    public function test_location_can_be_shown()
    {
        $location = Location::factory()->create(['name' => 'Aula']);

        $this->getJson('/api/location/'.$location->id)
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Aula']);
    }

    public function test_missing_location_returns_404()
    {
        $this->getJson('/api/location/99999999')->assertStatus(404);
    }

    public function test_store_is_forbidden_for_regular_users()
    {
        // LocationPolicy::create() is false; only the before() admin/operator
        // short-circuit can pass, so a regular user is rejected.
        $this->actingAs($this->makeUser())
            ->postJson('/api/location', ['name' => 'X', 'floor' => 0])
            ->assertStatus(403);
    }

    public function test_operator_can_update_a_location()
    {
        $location = Location::factory()->create(['name' => 'Old']);
        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);

        $this->actingAs($operator)
            ->putJson('/api/location/'.$location->id, ['name' => 'Renamed'])
            ->assertStatus(200);

        $this->assertDatabaseHas('locations', ['id' => $location->id, 'name' => 'Renamed']);
    }

    public function test_update_is_forbidden_for_regular_users()
    {
        $location = Location::factory()->create();

        $this->actingAs($this->makeUser())
            ->putJson('/api/location/'.$location->id, ['name' => 'Nope'])
            ->assertStatus(403);
    }

    public function test_operator_can_delete_a_location()
    {
        $location = Location::factory()->create();
        $operator = $this->makeUser();
        $this->grant($operator, PermissionType::Operator->value);

        $this->actingAs($operator)
            ->deleteJson('/api/location/'.$location->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('locations', ['id' => $location->id]);
    }

    public function test_delete_is_forbidden_for_regular_users()
    {
        $location = Location::factory()->create();

        $this->actingAs($this->makeUser())
            ->deleteJson('/api/location/'.$location->id)
            ->assertStatus(403);
    }

    public function test_location_events_listing()
    {
        $event = $this->makeEvent();

        $this->getJson('/api/location/'.$event->location_id.'/events')
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $event->id]);
    }

    public function test_location_current_events_listing()
    {
        $event = $this->makeEvent([
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);

        $this->getJson('/api/location/'.$event->location_id.'/current_events')
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $event->id]);
    }
}
