<?php

namespace App\Http\Controllers\E5N;

use App\EJGClass;
use App\Event;
use App\Presentations;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Endroid\QrCode;


use App\Http\Resources\Student as StudentResource;
use App\Score;

class E5NController extends Controller
{

    public function presentations(){
        return view('e5n.presentations');
    }
    public function attendancesheet($code){
        $presentation = \App\Presentation::where('code',$code);
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
            "classes" => \App\EJGClass::limit(10)->get()
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
        \App\Student::updatedatabase();
        \App\Presentation::query()->truncate();
        Event::query()->truncate();

        return view('e5n.reset');
    }

    public function codes(){
        return view('e5n.codes',[
            'students' => \App\Student::all()
        ]);
    }



}


