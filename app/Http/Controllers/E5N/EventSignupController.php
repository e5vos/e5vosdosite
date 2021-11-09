<?php

namespace App\Http\Controllers\E5N;


use App\Http\Controllers\{
    Controller
};
use App\Models\{
    Event,
    EventSignup
};

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Gate,
};


class EventSignupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('e5n.eventSignups.index');
    }
    public function show($eventCode){
        return view('e5n.eventSignups.show',["event"=> Event::firstWhere('code',$eventCode)]);
    }


    public function store(Request $request, $eventCode){
        Gate::authorize('create',EventSignup::class);
        $event = Event::where('code',$eventCode)->firstOrFail();
        Gate::authorize('signup',$event);
        $request->user()->signUp($event);
    }


    public function destroy(Request $request, $eventCode){
        $event = Event::where('code',$eventCode)->firstOrFail();
        $signUp = $request->user()->signups()->whereBelongsTo($event)->first();
        Gate::authorize('delete',$signUp);
        $signUp->delete();
    }

    /**
     * Returns the selected presentation of user
     *
     * @param  Request $request
     * @return void
     */
    public function getSelectedPresentations(Request $request){
        return $request->user()->presentations()->get();
    }



}
