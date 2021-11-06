<?php

namespace App\Http\Controllers\E5N;


use App\Http\Controllers\{
    Controller
};
use App\Models\{
    Event
};

use Illuminate\Support\Facades\{
    Gate,
    Request
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


    public function store(Request $request){
        Gate::authorize('create',EventSignup::class);

        $event = Event::findOrFail($request->input("event"));
        $request->user()->signUp($event);
    }


    public function destroy(Request $request, $event){
        $signUp = $request->user()->signups()->where('event_id',$event)->first();
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
