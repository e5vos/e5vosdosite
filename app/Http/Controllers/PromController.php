<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromController extends Controller
{
    public function admin(){
        Gate::authorize('e5n-admin');
        return view('prom.adminboard');
    }

    public function seatselect(){

    }
}
