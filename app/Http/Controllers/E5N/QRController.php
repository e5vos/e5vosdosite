<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};

use Illuminate\Http\Request;

use App\Models\{
    Event,
    User,
    Team
};

use Illuminate\Support\Facades\{
    Gate,
};

class QRController extends Controller
{
    public function scanEvent(Request $request){
        $event = Event::where('code',$request->input('code'))->firstOrFail();
        Gate::authorize('signup',$event);
        $request->user()->signUp($event);
    }
    public function scanUser(Request $request){
        $event = Event::where('code',$request->input('event'))->firstOrFail();
        Gate::authorize('signup',$event);
        User::where('code',$request->input('code'))->firstOrFail()->signUp($event);
    }
    public function scanTeam(Request $request){
        $event = Event::where('code',$request->input('event'))->firstOrFail();
        Gate::authorize('signup',$event);
        Team::where('code',$request->input('code'))->firstOrFail()->signUp($event);
    }

    public function scan(Request $request){
        switch($request->input('context')){
            case "event":
                return $this->scanEvent($request);
            case "user":
                return $this->scanUser($request);
            case "team":
                return $this->scanTeam($request);
            default:
            break;
        }
    }
}
