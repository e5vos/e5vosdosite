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
use App\Http\Resources\AttendanceResource;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Cache,
    DB,
};
use App\Http\Resources\EventResource;
use App\Http\Resources\SlotResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\TeamResource;

class EventController extends Controller
{

    /**
     * return event list
     * @return \Illuminate\Http\Response
     */
    public function index(int $slotId = null)
    {
        return !isset($slotId) ?
            Cache::rememberForever('e5n.events.all', fn () => EventResource::collection(Event::all()->load('slot', 'location'))->jsonSerialize()) :
            Cache::rememberForever('e5n.events.slot.'.$slotId, fn () => EventResource::collection(Event::with('slot', 'location')->where('slot_id', $slotId)->get()->load('slot', 'location'))->jsonSerialize());
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
        if ($request->starts_at < $slot->starts_at) {
            $request->starts_at = $slot->starts_at;
        }
        if ($request->ends_at > $slot->ends_at) {
            $request->ends_at = $slot->ends_at;
        }
        $event = Event::create($request->all());
        $event = new EventResource($event);
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        return Cache::rememberForever('e5n.events.'.$event->id, fn () => $event->jsonSerialize());
    }

    /**
     * return specific event
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return Cache::rememberForever('e5n.events.'.$id, function () use ($id) {$data = new EventResource(Event::findOrFail($id)->load('slot', 'location')); return $data->jsonSerialize();});
    }

    /**
     * edit a specific event.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $eventId)
    {
        $slot = Slot::find($request->slot_id);
        if (!isset($request->signup_type)) {
            $request->signup_deadline = null;
            $request->capacity = null;
        } else {
            $request->signup_deadline = $request->signup_deadline ?? $slot->starts_at;
        }
        if ($request->starts_at < $slot->starts_at) {
            $request->starts_at = $slot->starts_at;
        }
        if ($request->ends_at > $slot->ends_at) {
            $request->ends_at = $slot->ends_at;
        }
        $event  = Event::findOrFail($eventId)->update($request->all());
        $event = new EventResource($event);
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forever('e5n.events.'.$eventId, $event->jsonSerialize());
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
        Cache::forget('e5n.events.slot.'.$event->slot_id);
        $event->delete();
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forget('e5n.events.'.$eventId);
        return response('Az esemény sikeresen törölve', 204);
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
        $event = new EventResource($event);
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forever('e5n.events.'.$event->id, $event->jsonSerialize());
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
        $event = new EventResource($event);
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forever('e5n.events.'.$event->id, $event->jsonSerialize());
        return Cache::get('e5n.events.'.$event->id);
    }

    /**
     * return all presentations
     * @return \Illuminate\Http\Response
     */
    public function presentations()
    {
        return Cache::rememberForever('e5n.events.presentations', fn () => SlotResource::collection(Slot::where('slot_type', SlotType::presentation)->get()->load('events'))->jsonSerialize());
    }

    /**
     * signup user or team to event
     */
    public function signup(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        if (!is_numeric($request->attender)) {
            $attender = strlen($request->attender) == 13 ? User::where('e5code', $request->attender)->firstOrFail() : Team::where('code', $request->attender)->firstOrFail();
        } else {
            $attender = User::findOrFail($request->attender);
        }
        Cache::forget('e5n.events.all');
        Cache::forget('e5n.events.presentations');
        Cache::forget('e5n.events.mypresentations.'.($attender->e5code ?? $attender->code));
        Cache::put('e5n.events.'.$event->id.'.signups', UserResource::collection($event->users()->get())->merge(TeamResource::collection($event->teams()->get()))->jsonSerialize());
        Cache::forget('e5n.events.slot.'.$event->id);
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
        Cache::forget('e5n.events.slot.'.$eventId);
        return response("", 204);
    }

    /**
     * make user atend at an event
     */
    public function attend(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $attender = is_numeric($request->attender) ? User::findOrFail($request->attender) : (strlen($request->attender) == 13 ? User::where('e5code', $request->attender)->firstOrFail() : Team::where('code', $request->attender)->firstOrFail());
        Cache::put('e5n.events.'.$event->id.'.signups', UserResource::collection($event->users())->merge(TeamResource::collection($event->teams()))->jsonSerialize());
        return response($attender->attend($event), 200);
    }

    /**
     * return all participating entities for an event
     */
    public function participants($eventId)
    {
        return Cache::rememberForever(
            'e5n.events.'.$eventId.'.signups',
            function () use ($eventId) {
                $event = Event::findOrFail($eventId)->load('users', 'teams');
                return UserResource::collection($event->users)->merge(TeamResource::collection($event->teams))->jsonSerialize();
            }
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
            fn () => EventResource::collection($user->presentations)->jsonSerialize()
        );
    }
}
