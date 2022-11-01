<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};


use App\Models\{
    Attendance,
    Event,
    TeamMemberAttendance,
    User,
    Team,
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
            Cache::rememberForever('e5n.events'.$slotId, fn () => Event::where('slot_id', $slotId)->get());
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
            fn () => Event::join('slots', 'events.slot_id', '=', 'slots.id')->where('slots.slot_type', SlotType::presentation)->get(['events.*'])
        );
    }

    /**
     * signup user or team to event
     */
    public function signup(Request $request, $eventId)
    {
        $event = Cache::rememberForever('e5n.events'.$eventId, fn()=> Event::findOrFail($eventId));
        $attender = strlen($request->attender) == 13 ? User::where('e5code', $request->attender)->firstOrFail() : Team::where('code', $request->attender)->firstOrFail();
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::put('e5n.events'.$event->id.'signups', $event->attendances()->get());
        return response($attender->signUp($event), 201);
    }

    /**
     * make user atend at an event
     */
    public function attend(Request $request, $eventId)
    {
        $event = Cache::rememberForever('e5n.events'.$eventId, fn()=> Event::findOrFail($eventId));
        $attender = strlen($request->attender) == 13 ? User::where('e5code', $request->attender)->firstOrFail() : Team::where('code', $request->attender)->firstOrFail();
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::put('e5n.events'.$event->id.'signups', $event->attendances()->with('user')->get());
        return response($attender->attend($event), 200);
    }

    /**
     * return all participating entities for an event
     */
    public function participants($eventId)
    {
        return Cache::rememberForever(
            'e5n.events'.$eventId.'signups',
            fn () => Event::findOrFail($eventId)->signuppers()
        );
    }

    /**
     * return all presentations where the user has signed up
     */
    public function myPresentations(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        return Cache::rememberForever(
            'e5n.events.mypresentations'.$user->id,
            fn () => $user->presentations()->get(['events.*'])
        );
    }
}
