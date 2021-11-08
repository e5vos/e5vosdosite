<?php

namespace App\Http\Controllers\E5N\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PresentationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use App\Models\EventSignup;

class PresentationAdminController extends Controller
{

    public function index(){
        Gate::authorize('viewAnyAttendance',Event::class);
        return view('e5n.admin.presentation.index',["presentations"=>Event::presentations()]);
    }

    public function show(Request $request, $presentationCode){
        $presentation = Event::where('code',$presentationCode)->firstOrFail();
        Gate::authorize('viewAttendance',$presentation);
        $presentationResource = new PresentationResource ($presentation);
        return view('e5n.admin.presentation.show',["presentationResource"=>$presentationResource]);


    }

    public function setAttendance(Request $request, $presentationCode){
        $signup = EventSignup::findOrFail($request->input('signup'));
        Gate::authorize('setAttendance',$signup->event);
        $signup->present = $request->input('present');
        $signup->save();
    }



}
