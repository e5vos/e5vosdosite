<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;


class EventController extends Controller
{
    public function scanner(){
        //Gate::authorize('e5n.scanner');
        return view('e5n.scanner',[
            'event' => Auth::user()->currentEvent(),
        ]);


    }
}
