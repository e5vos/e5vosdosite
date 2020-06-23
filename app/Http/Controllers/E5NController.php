<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class E5NController extends Controller
{
    public function presentations(){

    }
    public function scanner(){

    }
    public function map(){

    }

    public function admin(){
        Gate::authorize('e5n-admin');
        return view('e5n.adminboard');
    }

}
