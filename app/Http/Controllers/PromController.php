<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;

class PromController extends Controller
{
    public function admin(){
        Gate::authorize('e5n-admin');
        return view('prom.adminboard');
    }


}
