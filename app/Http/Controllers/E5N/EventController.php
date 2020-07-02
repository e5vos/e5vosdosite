<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function scanner(){
        Gate::authorize('e5n.scanner');
        $event = Auth::user()->currentEvent();
        return view('e5n.scanner',[
            'event'=>$event,
        ]);

    }
}
