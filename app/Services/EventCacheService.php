<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Cache;

class EventCacheService
{
    public static function forgetEvent(int $eventId, ?int $slotId = null): void
    {
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forget("e5n.events.{$eventId}");
        Cache::forget("e5n.events.{$eventId}.signups");
        if ($slotId) {
            Cache::forget("e5n.events.slot.{$slotId}");
        }
    }

    public static function forgetSignup(int $eventId, string $attenderKey): void
    {
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forget("e5n.events.{$eventId}");
        Cache::forget("e5n.events.{$eventId}.signups");
        Cache::forget("e5n.events.mypresentations.{$attenderKey}");

        $slotId = Event::find($eventId)?->slot_id;
        if ($slotId) {
            Cache::forget("e5n.events.slot.{$slotId}");
        }
    }
}
