<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Event;

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
        return view('e5n.eventSignups.show',["event"=> \App\Event::firstWhere('code',$eventCode)]);
    }


    public function store(Request $request){
        dd("asd");
        Gate::authorize('store',EventSignup::class);

        $event = \App\Event::findOrFail($request->input("event"));
        $request->user->signUp($event);
    }


    public function destroy(Request $request){
        $signUp = $request->user->signups()->where('events.id',$request->input('event'))->first();
        Gate::authorize('destroy',$signUp);
        $signUp->delete();
    }

    /**
     * Returns the selected presentation of user
     *
     * @param  mixed $request {}
     * @return void
     */
    public function getSelectedPresentations(Request $request){
        dd("asd");
        DD($request->user);
        Gate::authorize("viewAny",EventSignup::class);
        return $request->user()->presentations()->get();
    }

    /**
     * Returns the selected presentation of user
     *
     * @param  mixed $request {}
     * @return void
     */
    public function getSelectedEvents(Request $request){
        return $request->user()->presentations()->get();
    }


}
