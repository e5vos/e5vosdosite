<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};


use App\Models\{
    Event
};

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Gate,
    Auth,
};


class EventController extends Controller
{


    public function index()
    {
        return view('e5n.events.index');
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


    public function edit($eventCode){

        $event = Event::where('code',$eventCode)->firstOrFail();

        Gate::authorize('update',$event);
        return view('e5n.events.edit',["event" => $event]);
    }

    public function update(Request $request, $eventCode){
        $event = Event::firstWhere('code',$eventCode);
        Gate::authorize('update',$event);

        if(!$event->is_presentation){
            abort(400);
        }

        if($event->code != $request->input('code') && !Event::where('code',$request->input('code'))->exists()){
            $event->code = $request->input('code');
        }
        $event->name = $request->input('name');
        $event->description = $request->input('description');
        $event->weight = $request->input('weight');
        $event->location_id = $request->input('location');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->organiser_name = $request->input('organiser_name');
        $event->capacity = $request->input('capacity');
        $event->slot = $request->input('slot') ;
        $event->is_presentation = $request->input('is_presentation') == "on";
        $event->save();

        return redirect()->route('event.show',$event->code);
    }

    public function destroy($presentationCode){
        $presentation = Event::firstWhere('code',$presentationCode);
        Gate::authorize('destroy', $presentation);
        $presentation->delete();
    }

    public function show($eventCode){
        return view('e5n.events.show');
    }

    /**
     * Returns the specified event.
     *
     * @param  string $event_name
     * @return \Illuminate\Http\Resources\Json\EventResourceCollection
     */
    public function event_data($code)
    {
        return Event::firstWhere('name', $code);
    }

    /** Returns the events with the specified weight.
     *
     *
     * @param  int $weight the weight of the events
     */
    public function weight($weight)
    {
        return Event::where('weight', $weight)->get();
    }

    public function ongoing()
    {
        return Event::currentEvents();
    }

    public function presentationSlot($slot){
        return Event::where('is_presentation',true)->where('slot',$slot)->get();
    }

    public function rate(Request $request){
        /**
         * @var \App\User
         */
        $user = Auth::user();
        if($user != null){
            $user->rate(Event::find($request->input('event_id')),$request->input('rating'));
        }
    }
}
