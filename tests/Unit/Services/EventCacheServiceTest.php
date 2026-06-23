<?php

namespace Tests\Unit\Services;

use App\Services\EventCacheService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class EventCacheServiceTest extends TestCase
{
    public function test_forget_event_clears_all_related_keys(): void
    {
        Cache::put('e5n.events.all', 'data', 60);
        Cache::put('e5n.events.presentations', 'data', 60);
        Cache::put('e5n.events.42', 'data', 60);
        Cache::put('e5n.events.42.signups', 'data', 60);

        EventCacheService::forgetEvent(42);

        $this->assertNull(Cache::get('e5n.events.all'));
        $this->assertNull(Cache::get('e5n.events.presentations'));
        $this->assertNull(Cache::get('e5n.events.42'));
        $this->assertNull(Cache::get('e5n.events.42.signups'));
    }

    public function test_forget_event_also_clears_slot_key_when_slot_id_given(): void
    {
        Cache::put('e5n.events.slot.7', 'data', 60);

        EventCacheService::forgetEvent(42, slotId: 7);

        $this->assertNull(Cache::get('e5n.events.slot.7'));
    }

    public function test_forget_event_does_not_clear_other_slot_keys(): void
    {
        Cache::put('e5n.events.slot.99', 'other', 60);

        EventCacheService::forgetEvent(42, slotId: 7);

        $this->assertSame('other', Cache::get('e5n.events.slot.99'));
    }

    public function test_forget_signup_clears_signup_and_presentation_keys(): void
    {
        Cache::put('e5n.events.all', 'data', 60);
        Cache::put('e5n.events.presentations', 'data', 60);
        Cache::put('e5n.events.42', 'data', 60);
        Cache::put('e5n.events.42.signups', 'data', 60);
        Cache::put('e5n.events.mypresentations.abc123', 'data', 60);

        EventCacheService::forgetSignup(42, 'abc123');

        $this->assertNull(Cache::get('e5n.events.all'));
        $this->assertNull(Cache::get('e5n.events.presentations'));
        $this->assertNull(Cache::get('e5n.events.42'));
        $this->assertNull(Cache::get('e5n.events.42.signups'));
        $this->assertNull(Cache::get('e5n.events.mypresentations.abc123'));
    }

    public function test_forget_signup_does_not_clear_other_attender_key(): void
    {
        Cache::put('e5n.events.mypresentations.other', 'data', 60);

        EventCacheService::forgetSignup(42, 'abc123');

        $this->assertSame('data', Cache::get('e5n.events.mypresentations.other'));
    }
}
