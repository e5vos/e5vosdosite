<?php

namespace Tests\Feature;

use App\Http\Controllers\E5N\EventController;
use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     *  A test to check if the event page is working.
     */
    public function test_events_can_be_requested()
    {
        $this->markTestSkipped('This test has not been implemented yet.');
        $response = $this->get('/api/events');

        $response->assertStatus(200);
    }
}
