<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};


use App\Models\{
    Event,
    Rating,
    User,
    Slot,
};

use App\Http\Resources\{
    EventResource,
    RatingResource,
    UserResource,
    UserResourceCollection,
    EventResourceCollection,
};

use App\Exception;
use App\Exceptions\DuplicateCodeException;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Auth,
    Cache,
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
        Cache::put('e5n.events'.$eventId, Event::findOrFail($eventId));
        return Cache::get('e5n.events'.$eventId);
    }

    /**
     * delete a specific event.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($eventId){
        $event = Event::findOrFail($eventId);
        $event->delete();
        Cache::forget('e5n.events.all');
        return Cache::pull('e5n.events'.$event->id, fn () => $event);
    }

    /**
     * restore a specific event.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($eventId){
        $event = Event::withTrashed()->findOrFail($eventId);
        $event->restore();
        Cache::forget('e5n.events.all');
        Cache::put('e5n.events'.$event->id, $event);
        return Cache::get('e5n.events'.$event->id);
    }
}
