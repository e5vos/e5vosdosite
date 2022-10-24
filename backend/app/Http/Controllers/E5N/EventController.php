<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};


use App\Models\{
    Attendance,
    Event,
};

use App\Helpers\SlotType;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Cache,
    DB,
};


class EventController extends Controller
{

    /**
     * return event list
     * @return \Illuminate\Http\Response
     */
    public function index(int $slotId = null)
    {
        return !isset($slotId) ?
            Cache::rememberForever('e5n.events.all', fn () => Event::all()) :
            Cache::rememberForever('e5n.events'.$slotId, fn () => Event::where('slot_Id', $slotId)->get());
    }

    /**
     * Create an event.
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = Event::create($request->all());
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        return Cache::rememberForever('e5n.events'.$event->id, fn () => $event);
    }

    /**
     * return specific event
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return Cache::rememberForever('e5n.events'.$id, fn () => Event::findOrFail($id));
    }

    /**
     * edit a specific event.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $eventId)
    {
        Event::findOrFail($eventId)->update($request->all());
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forever('e5n.events'.$eventId, Event::findOrFail($eventId));
        return Cache::get('e5n.events'.$eventId);
    }

    /**
     * delete a specific event.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($eventId)
    {
        $event = Event::findOrFail($eventId);
        $event->delete();
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        return Cache::pull('e5n.events'.$event->id, fn () => $event);
    }

    /**
     * restore a specific event.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($eventId)
    {
        $event = Event::withTrashed()->findOrFail($eventId);
        $event->restore();
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forever('e5n.events'.$event->id, $event);
        return Cache::get('e5n.events'.$event->id);
    }

    /**
     * close signup of event
     * @return \Illuminate\Http\Response
     */
    public function close_signup($eventId)
    {
        $event = Event::findOrFail($eventId);
        $event->signup_deadline = now()->format('Y-m-d H:i:s');
        $event->save();
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::put('e5n.events'.$event->id, $event);
        return Cache::get('e5n.events'.$event->id);
    }

    /**
     * return all presentations
     * @return \Illuminate\Http\Response
     */
    public function presentations()
    {
        return Cache::rememberForever(
            'e5n.events.presentations',
            fn () => DB::table('events')
                ->join('slots', 'events.slot_id', '=', 'slots.id')
                ->where('slots.slot_type', SlotType::presentation)
                ->get()
        );
    }

    /**
     * signup user or team to event
     */
    public function signup(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId)->append('signups');
        $attender = json_decode($request->attender);
        $attendance = Attendance::create(['event_id' => $event->id, $attender->type.'_id' => $attender->id]);
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::put('e5n.events'.$event->id.'signups', $event->signups);
        return response($attendance);
    }

    /**
     * make user atend at an event
     */
    public function attend(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId)->append('signups');
        $attender = json_decode($request->attender);
        $attendance = Attendance::where('event_id', $eventId)->where($attender->type.'_id', $attender->id)->first() ??
            Attendance::create(['event_id' => $event->id, $attender->type.'_id' => $attender->id]);
        $attendance->update(['is_present' => true]);
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::put('e5n.events'.$event->id.'signups', $event->signups);
        return response($attendance);
    }
}
