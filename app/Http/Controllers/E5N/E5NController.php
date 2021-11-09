<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};
use App\Http\Resources\EventResource;
use App\Models\{
    EJGClass,
    Event,
    User
};

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Gate
};

use Endroid\QrCode;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class E5NController extends Controller
{

    public function presentations(){
        return view('e5n.presentations');
    }
    public function attendancesheet($code){
        $presentation = Event::where('code',$code);
        return view('e5n.attendance',[
            'students' => $presentation->students()->get(), // contains student data
            'signups' => $presentation->signups()->get(), // contains attendance bool
        ]);
    }

    public function map(){
        return view('e5n.map');
    }

    public function index(){
        return view('e5n.index',[
            "classes" => EJGClass::orderBy('points','DESC')->limit(10)->get()
        ]);
    }

    public function admin(){
        Gate::authorize('e5n-admin');
        return view('e5n.adminboard', [
            'events' => Event::all(),
            'classRanks' => EJGClass::all('id','name','points')->sortByDesc('points'),
        ]);
    }

    public function reset(){
        Gate::authorize('e5n-admin');
        Event::query()->truncate();
        Event::query()->truncate();

        return view('e5n.reset');
    }

    public function scanner(Request $request, $eventCode){
        $event = Event::where('code',$eventCode)->firstOrFail();
        Gate::authorize('signup',$event);
        $eventResource = new EventResource($event);
        return view('e5n.events.scanner',["event" => $eventResource]);
    }

    public function calculatePoints(){
        Gate::authorize('ADM');
        foreach(EJGClass::all() as $ejgclass){
            $ejgclass->calculatePoints();
        }
    }

}


