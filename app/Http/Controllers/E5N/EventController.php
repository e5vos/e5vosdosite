<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Event as EventResource;
use App\Event;
use App\Rating;
use App\User;

class EventController extends Controller
{
    public function scanner(){
        Gate::authorize('e5n.scanner');
        return view('e5n.scanner',[
            'event' => Auth::user()->currentEvent(),
        ]);
    }

    /**
     * Shows the eventviewer page.
     *
     * @return view
     */
    public function home()
    {
        return view('e5n.programs.events');
    }

    /**
     * Returns view and Json data for a specific event
     *
     * @param  string $event_name
     * @return void
     */
    public function event($day = null, $event_name = null)
    {
            return view('e5n.programs.event');
    }

    /**
     * Returns the Json data for the specified event.
     *
     * @param  string $event_name
     * @param  string $day
     * @return \Illuminate\Http\Resources\Json\EventResourceCollection
     */
    public function event_data($day, $event_name)
    {
        $day = strtolower(strtok($day, '%20'));
        $day = [
            "hetfő" => 0,
            "kedd" => 1,
            "szerda" => 2,
            "csütörtök" => 3,
            "péntek" => 4,
            "szombat" => 5,
            "vasárnap" => 6
        ][$day];
        return Event::where('name', '=', $event_name)->whereRaw('WEEKDAY(`start`)', '=', $day)->get();
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

    public function rate(Request $request){
        /**
         * @var \App\User
         */
        $user = Auth::user();
        if($user != null){
            $user->rate(\App\Event::find($request->input('event_id')),$request->input('rating'));
        }
    }
}
