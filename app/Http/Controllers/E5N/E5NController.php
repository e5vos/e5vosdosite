<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};

use App\Models\{
    EJGClass,
    Event
};

use Illuminate\Support\Facades\{
    Gate
};

use Endroid\QrCode;


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

    public function home(){
        return view('e5n.home',[
            "classes" => EJGClass::limit(10)->get()
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



}


