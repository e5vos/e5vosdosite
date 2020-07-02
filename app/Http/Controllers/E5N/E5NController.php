<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;


class E5NController extends Controller
{

    public function presentations(){
        return view('e5n.presentations');
    }
    public function attendancesheet($code){
        $presentation = App\Presentation::where('code',$code);
        return view('e5n.attendance',[
            'students' => $presentation->students(), // contains student data
            'signups' => $presentation->signups(), // contains attendance bool
        ]);
    }


    public function map(){
        return view('e5n.map');
    }

    public function admin(){
        //Gate::authorize('e5n-admin');
        return view('e5n.adminboard');
    }

    public function reset(){
        Gate::authorize('e5n-admin');
        App\Student::updatedatabase();
        Presentation::query()->truncate();
        Event::query()->truncate();

        return view('e5n.reset');
    }

}


