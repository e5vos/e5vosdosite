<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};

use App\Models\{
    Event,
    User,
    Setting
};

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Gate,
};



class PresentationController extends Controller
{

    public function index() {
        return view('e5n.presentations.index');
    }


    /**
     * signs a student up to a presentation
     *
     * @param  mixed $request {}
     * @return void
     */
    public function signUp(Request $request, $eventCode){
        if(!Setting::check('e5nPresentationSignup')){
            abort(403,"No e5n");
        }
        $presentation = Event::where('code',$eventCode)->firstOrFail();
        Gate::authorize('presentationsignUp',$presentation);
        $request->user()->signUp($presentation);
    }

    /**
     * revoke a students aoolication to a presentation
     *
     * @param  mixed $request
     * @return void
     */

    public function deleteSignUp(Request $request){
        $student_id = $request->session()->get("student_id");
        if($student_id == null){
            abort(403, "Student not authenticated");
        }
        $student = User::find($student_id);
        $student->signups()->where("event_id",$request->input("presentation"))->delete();
    }
}
