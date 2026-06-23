<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function test_dump_state_posts_to_webhook_and_returns_no_content()
    {
        Http::fake();

        // Outside production the endpoint skips its auth guard and forwards the
        // payload to the configured Discord webhook.
        $this->postJson('/api/dumpState', ['dump' => 'coverage payload'])
            ->assertStatus(204);

        Http::assertSentCount(1);
    }

    public function test_dump_state_truncates_overly_long_dumps()
    {
        Http::fake();

        $this->postJson('/api/dumpState', ['dump' => str_repeat('a', 5000)])
            ->assertStatus(204);

        Http::assertSent(function ($request) {
            // The controller caps the forwarded content at 1900 characters.
            return strlen($request['content']) <= 1900 + strlen('```json```');
        });
    }
}
