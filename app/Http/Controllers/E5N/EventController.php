<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};


use App\Models\{
    Event,
    Rating,
    User,
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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\{
    Gate,
    Auth,
    Cache,
};


class EventController extends Controller
{

    /**
     * Show event list
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Cache::has('e5n.eventList')){ $events = Cache::get('e5n.eventList');}
        else {
            $events = Event::orderByRAW('WEEKDAY(`start`)')->get()->groupBy(function($event){
                return $event->start->dayOfWeek;
            });
            Cache::put('e5n.eventList', $events, now()->addMinute());
        }

        return view('e5n.events.index',[
            'events' => $events
        ]
    );
    }

    /**
     * Show the form for creating a new event.
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        Gate::authorize('create',Event::class);
        return view('e5n.events.create');
    }



    /**
     * Store a newly created event in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        Gate::authorize('create',Event::class);
        $event = new Event();
        $event->code = $request->input('code');
        $event->name = $request->input('title');
        $event->save();

        return redirect()->route('event.edit',$event->code);

    }

    /**
     * Show the form to edit a specific event.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $eventCode){

        $event = Event::withTrashed()->where('code',$eventCode)->firstOrFail();

        Gate::authorize('update',$event);
        if($event->trashed() && !$request->user()->isAdmin()){
            abort(403, 'Ez az esemény törölve lett, ha szervező vagy és szeretnéd visszaszerezni, akkor írj az e5n@e5vos.hu-ra.');
        }
        return view('e5n.events.edit',["event" => $event]);
    }

    /**
     * Update the specified event in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $eventCode){
        $event = Event::firstWhere('code',$eventCode);
        Gate::authorize('update',$event);
        if($event->code != $request->input('code')){
            if(Event::where('code',$request->input('code'))->exists()) {
                throw new DuplicateCodeException("Event code taken");
            }
            else {
                $event->code = $request->input('code');
            }
        }

        if($event->code != $request->input('code') && !Event::where('code',$request->input('code'))->exists()){
            $event->code = $request->input('code');
        }
        $event->name = $request->input('name');
        $event->description = $request->input('description');
        $starttime = strtotime($request->input('start'));
        $endtime = strtotime($request->input('end'));

        if ($starttime && $endtime && $endtime > $starttime){
            $event->start = $request->input('start');
            $event->end = $request->input('end');
        }

        $event->organiser_name = $request->input('organiser_name');

        if($request->user()->isAdmin()){
            $event->weight = $request->input('weight');
            $event->capacity = $request->input('capacity');
            $event->slot = $request->input('slot') ;
            $event->location_id = $request->input('location');
            $event->is_presentation = $request->input('is_presentation') == "on";
        }

        $event->save();

        return redirect()->route('event.edit',$event->code);
    }

    /**
     * Remove the specified event from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($eventCode){
        $event = Event::where('code',$eventCode)->firstOrFail();
        Gate::authorize('delete', $event);
        $event->delete();
        Cache::forget('e5n.event.'.$eventCode);
        return redirect()->route('event.edit',$eventCode);
    }

    /**
     * Restore the specified event to storage.
     * @return \Illuminate\Http\Response
     */
    public function restore($eventCode) {
        $event = Event::withTrashed()->where('code',$eventCode)->firstOrFail();
        Gate::authorize('restore', $event);
        $event->restore();
        Cache::forget('e5n.event.'.$eventCode);
        return redirect()->route('event.edit',$event->code);
    }

    /**
     * Show a specific event's page
     * @param \Illuminate\Http\Response $request
     * @param string $eventCode
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $eventCode){
        $event = Event::where('code',$eventCode)->firstOrFail();
        $eventResource = new EventResource($event);
        if(Auth::check()){
            $userRating = $request->user()->ratings()->whereBelongsTo($event)->first()->value;
        }else{
            $userRating = $event->rating;
        }
        return view('e5n.events.show', [
            'event'=>$eventResource,
            'isUser' => Auth::check() ? "true" : "false",
            'userRating' => $userRating,
        ]);
    }

    /**
     * Add organiser to event
     * @param \Illuminate\Http\Request $request
     * @param string $eventCode
     * @return \Illuminate\Http\Response
     */
    public function addOrganiser(Request $request, $eventCode)
    {
        $event = Event::where('code',$eventCode)->firstOrFail();
        Gate::authorize('update', $event);
        try {
            $user = User::where('email', $request->input('email'))->firstOrFail();
        } catch (ModelNotFoundException $e){
            return redirect()->route('event.edit', $eventCode);
        }
        $event->addOrganiser($user);
        return redirect()->route('event.edit', $eventCode);
    }


    /**
     * Remove organiser from event
     * @param \Illuminate\Http\Request $request
     * @param string $eventCode
     * @return \Illuminate\Http\Response
     */
    public function removeOrganiser(Request $request, $eventCode)
    {
        $event = Event::where('code',$eventCode)->firstOrFail();
        Gate::authorize('update', $event);
        $user = User::findOrFail($request->input('orga'));
        $event->removeOrganiser($user);
        return redirect()->route('event.edit', $eventCode);
    }

    /**
     * Returns the specified eventdata.
     *
     * @param string $eventCode
     * @return \Illuminate\Http\Response
     */
    public function event_data($eventCode)
    {
        if (false && Cache::has('e5n.event.' . $eventCode)) {$event = Cache::get('e5n.event.' . $eventCode);}
        else {
            $event = new EventResource(Event::where('code',$eventCode)->firstOrFail());
            Cache::put('e5n.event.' . $eventCode, $event, now()->addMinutes(3));
        }
        return $event;
    }

    /** Returns the events with the specified weight.
     *
     *
     * @param  int $weight the weight of the events
     * @return \Illuminate\Http\Response
     */
    public function getEventByWeight($weight)
    {
        return new EventResource(Event::where('weight', $weight));
    }

    /**
     * Returns currently running events.
     * @return \Illuminate\Http\Response
     */
    public function ongoing()
    {
        return new EventResourceCollection(Event::currentEvents()->paginate());
    }

    /**
     * Returns all events within a specified time slot
     * @param $slot int the specific time slot
     * @return \Illuminate\Database\Eloquent\Collection events in the specified time slot
     */
    public function presentationSlot($slot){

        if(!Cache::has('e5n.events.presentations.'.$slot)){
            $slotEvents = Event::where('is_presentation',true)->where('slot',$slot)->get()->filter(function($event){
                return $event->capacity == null || $event->capacity > $event->occupancy;
            });
            Cache::put('e5n.events.presentations.'.$slot,$slotEvents,now()->addSeconds(30));
            return $slotEvents;
        }else{
            return Cache::get('e5n.events.presentations.'.$slot);
        }
    }

    /**
     * Rate a specific event.
     * @param \Illuminate\Http\Request $request
     * @param string $eventCode
     * @return \Illuminate\Http\Response
     */
    public function rate(Request $request, $eventCode){
        $event = Event::where('code',$eventCode)->firstOrFail();
        Gate::authorize('rate',$event);
        $request->user()->rate($event,$request->input('rating'));
    }
}
