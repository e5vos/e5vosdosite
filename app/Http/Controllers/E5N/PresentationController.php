<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class PresentationController extends Controller
{
    function presentations(){
        return Presentation::all()->toJson();
    }
    function attendanceSheet($code){
        $presentation = Presentation::where('code',$code);

        return json_encode([
            'students' => $presentation->students()->pluck('name')->toArray(), // contains student data
            'signups' => $presentation->signups()->toArray(), // contains attendance bool
        ]);
    }

    function toggleAttendance($signup){
        PresentationSignup::findOrFail($signup)->toggle();
    }

}
