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
    Slot,
};

use App\Helpers\SlotType;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Cache,
    DB,
};
use App\Http\Resources\EventResource;


class EventController extends Controller
{

    /**
     * return event list
     * @return \Illuminate\Http\Response
     */
    public function index(int $slotId = null)
    {
        return !isset($slotId) ?
            Cache::rememberForever('e5n.events.all', fn () => EventResource::collection(Event::all())->jsonSerialize()) :
            Cache::rememberForever('e5n.events.slot.'.$slotId, fn () => EventResource::collection(Event::where('slot_id', $slotId)->get())->jsonSerialize());
    }

    /**
     * Create an event.
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slot = Slot::find($request->slot_id);
        if (!isset($request->signup_type)) {
            $request->signup_deadline = null;
            $request->capacity = null;
        } else {
            $request->signup_deadline = $request->signup_deadline ?? $slot->starts_at;
        }
        if ($request->stats_at )
        $event = Event::create($request->all());
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        return Cache::rememberForever('e5n.events.'.$event->id, fn () => $event);
    }

    /**
     * return specific event
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return Cache::rememberForever('e5n.events.'.$id, function () use ($id) {$data = new EventResource(Event::findOrFail($id)); return $data->jsonSerialize();});
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
        Cache::forever('e5n.events.'.$eventId, Event::findOrFail($eventId));
        return Cache::get('e5n.events.'.$eventId);
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
        return Cache::pull('e5n.events.'.$event->id, fn () => $event);
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
        Cache::forever('e5n.events.'.$event->id, $event);
        return Cache::get('e5n.events.'.$event->id);
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
        Cache::put('e5n.events.'.$event->id, $event);
        return Cache::get('e5n.events.'.$event->id);
    }

    /**
     * return all presentations
     * @return \Illuminate\Http\Response
     */
    public function presentations()
    {
        return response()->json(Cache::rememberForever(
            'e5n.events.presentations',
            fn () => Slot::with('events')->where('type', SlotType::presentation)->get()
        ), 200);
    }

    /**
     * signup user or team to event
     */
    public function signup(Request $request, $eventId)
    {
        $event = Cache::rememberForever('e5n.events.'.$eventId, fn() => Event::findOrFail($eventId));
        if (!is_numeric($request->attender)) {
            $attender = strlen($request->attender) == 13 ? User::where('e5code', $request->attender)->firstOrFail() : Team::where('code', $request->attender)->firstOrFail();
        } else {
            $attender = User::findOrFail($request->attender);
        }
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forget('e5n.events.mypresentations.'.($attender->e5code ?? $attender->code));
        Cache::put('e5n.events.'.$event->id.'.signups', $event->attendances()->get());
        return response($attender->signUp($event), 201);
    }

    /**
     * unsignup user or team from event
     */
    public function unsignup(Request $request, $eventId)
    {
        $attender = strlen($request->attender) == 13 || is_numeric($request->attender) ? 'user_id' : 'team_code';
        $attenderId = $attender === 'user_id' && !is_numeric($request->attender) ? User::where('e5code', $request->attender)->firstOrFail()->id : $request->attender;
        $attendance = Attendance::where('event_id', $eventId)->where($attender, $attenderId)->firstOrFail();
        $attendance->delete();
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forget('e5n.events.mypresentations.'.$request->attender);
        Cache::forget('e5n.events.'.$eventId.'.signups');
        return response("", 204);
    }

    /**
     * make user atend at an event
     */
    public function attend(Request $request, $eventId)
    {
        $event = Cache::rememberForever('e5n.events'.$eventId, fn()=> Event::findOrFail($eventId));
        $attender = is_numeric($request->attender) ? User::findOrFail($request->attender) : (strlen($request->attender) == 13 ? User::where('e5code', $request->attender)->firstOrFail() : Team::where('code', $request->attender)->firstOrFail());
        Cache::put('e5n.events.'.$event->id.'.signups', $event->signuppers());
        return response($attender->attend($event), 200);
    }

    /**
     * return all participating entities for an event
     */
    public function participants($eventId)
    {
        return Cache::rememberForever(
            'e5n.events.'.$eventId.'.signups',
            fn () => Event::findOrFail($eventId)->signuppers()
        );
    }

    /**
     * return all presentations where the user has signed up
     */
    public function myPresentations(Request $request)
    {
        $user = User::findOrFail($request->user()->id)->load('presentations');
        return Cache::rememberForever(
            'e5n.events.mypresentations.'.$user->e5code,
            fn () => $user->presentations
        );
    }
}
