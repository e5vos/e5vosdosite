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

use App\Exception;
use App\Exceptions\DuplicateCodeException;
use App\Http\Resources\EventResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\{
    Gate,
    Auth,
    Cache,
};


class EventController extends Controller
{


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


    public function create(Request $request){
        Gate::authorize('create',Event::class);
        return view('e5n.events.create');
    }




    public function store(Request $request){
        Gate::authorize('create',Event::class);
        if(!$request->user()->isAdmin()){
            abort(403);
        }

        $event = new Event();
        $event->code = $request->input('code');
        $event->name = $request->input('title');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->organiser_name = $request->input('organiser_name');
        $event->capacity = $request->input('capacity');
        $event->is_presentation = $request->input('is_presentation');
        $event->slot = $request->input('slot');
        $event->save();

    }


    public function edit(Request $request, $eventCode){

        $event = Event::withTrashed()->where('code',$eventCode)->firstOrFail();

        Gate::authorize('update',$event);
        if($event->trashed() && !$request->user()->isAdmin()){
            abort(403, 'Ez az esemény törölve lett, ha szervező vagy és szeretnéd visszaszerezni, akkor írj az e5n@e5vos.hu-ra.');
        }
        return view('e5n.events.edit',["event" => $event]);
    }

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

    public function destroy($eventCode){
        $event = Event::where('code',$eventCode)->firstOrFail();
        Gate::authorize('delete', $event);
        $event->delete();
        Cache::forget('e5n.event.'.$eventCode);
        return redirect()->route('event.edit',$eventCode);
    }

    public function restore($eventCode) {
        $event = Event::withTrashed()->where('code',$eventCode)->firstOrFail();
        Gate::authorize('restore', $event);
        $event->restore();
        Cache::forget('e5n.event.'.$eventCode);
        return redirect()->route('event.edit',$event->code);
    }

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
     * @param  string $event_name
     * @return \Illuminate\Http\Resources\Json\EventResourceCollection
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
     */
    public function getEventByWeight($weight)
    {
        return Event::where('weight', $weight)->get();
    }

    public function ongoing()
    {
        return Event::currentEvents();
    }

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
    public function rate(Request $request, $eventCode){
        $event = Event::where('code',$eventCode)->firstOrFail();
        Gate::authorize('rate',$event);
        $request->user()->rate($event,$request->input('rating'));
    }
}
