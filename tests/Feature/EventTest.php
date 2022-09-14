<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     * Test if events can be created.
     *
     */
    public function testEventCreation()
    {
        $response = $this->post('/events', [
            'name' => 'Test Event',
            'description' => 'This is a test event.',
            'start_date' => '2020-01-01',
            'end_date' => '2020-01-02',
            'start_time' => '12:00:00',
            'end_time' => '13:00:00',
            'location' => 'Test Location',
            'address' => 'Test Address',
            'city' => 'Test City',
            'state' => 'Test State',
            'zip' => '12345',
            'country' => 'Test Country',
            'contact_name' => 'Test Contact',
            ]
        );
    }
}
