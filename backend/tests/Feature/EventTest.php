<?php

namespace Tests\Feature;

use App\Models\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A test to check if events can be created.
     */
    public function test_events_can_be_created()
    {
        $this->markTestSkipped('This test has not been implemented yet.');
        $event = Event::factory()->count(1)->make();
        $event->slot_id = 1;
        $response = $this->post('/api/event', $event->toArray());
        $response->assertStatus(201);

    }


    /*
    * A test to check if events can be updated.
    */
    public function test_events_can_be_updated()
    {
        $this->seed();
        $this->markTestSkipped('This test has not been implemented yet.');
        $event = Event::inRandomOrder()->first();
        $event->title = 'New Title';

        $response = $this->put('/api/event/' . $event->id, $event->toArray());
        $response->assertStatus(403);

        $this->actingAs($event->permissions()->firstWhere('code', 'ORG')->user);
        $response = $this->put('/api/event/' . $event->id, $event->toArray());
        $response->assertStatus(200);

        $this->actingAs($event->permissions()->firstWhere('code', 'ADM')->user);
        $response = $this->put('/api/event/' . $event->id, $event->toArray());
        $response->assertStatus(200);
    }


    /**
     *  A test to check if the event page is working.
     */
    public function test_events_can_be_requested()
    {
        $this->markTestSkipped('This test has not been implemented yet.');
        $response = $this->get('/api/events');
        $response->assertStatus(200);
    }

    /**
     * A test to check if events can be deleted.
     */
    public function test_events_can_be_deleted()
    {
        $this->seed();
        $this->markTestSkipped('This test has not been implemented yet.');
        $event = Event::inRandomOrder()->first();

        $response = $this->delete('/api/event/' . $event->id);
        $response->assertStatus(403);

        $this->actingAs($event->permissions()->firstWhere('code', 'ADM')->user);
        $response = $this->delete('/api/event/' . $event->id);
        $response->assertStatus(200);
    }

    /**
     * A test to check if events can be requested.
     */
    public function test_event_can_be_requested()
    {
        $this->seed();
        $this->markTestSkipped('This test has not been implemented yet.');
        $event = Event::inRandomOrder()->first();

        $response = $this->get('/api/event/' . $event->id);
        $response->assertStatus(200);
    }
}
