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
     * returns teacher admin board view opener
     *
     * @return view
     */
    public function attendanceOpener() {
        return view('e5n.presentations.attendancesheetopener');
    }

    /**
     * returns general teacher board view
     *
     * @return view
     */
    public function attendanceViewer(){
        return view('e5n.presentations.attendancesheet');
    }

    /**
     * returns the presentations adminboard
     *
     * @return view
     */
    public function presAdmin(){
        return view('e5n.presentations.admin');
    }


    /**
     * Get attendnce sheet of presentation
     *
     * @param  string $code Code of Presentation
     * @return void
     */
    public function attendanceSheet($code){
        $presentation = Event::firstWhere('code',$code);

        return json_encode([
            'presentation' => $presentation->id,
            'names' => $presentation->students()->get()->pluck('name'), // contains student data
            'signups' => $presentation->signups()->get()->makeHidden('presentation_id'), // contains attendance bool
        ]);
    }

    /**
     * Toggle attendance of student in a presentation
     * @param string $prezcode Code of the presentation
     * @param int $signup id of the signup to toggle
     */
    public function toggleAttendance($prezcode,$signup){
        Event::where('code',$prezcode)->first()->signups()->findOrFail($signup)->toggle();
    }


    /**
     * signs a student up to a presentation
     *
     * @param  mixed $request {}
     * @return void
     */
    public function signUp(Request $request){
        if(!Setting::check('e5nPresentationSignup')){
            abort(403,"No e5n");
        }
        $student_id = $request->session()->get("student_id");
        if($student_id == null){
            abort(403, "Student not authenticated");
        }
        $student = User::find($student_id);
        $presentation = Event::find($request->input("presentation"));
        $student->signUp($presentation);
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




    /**
     * Returns students who didn't sign up to any presentation in $slot
     *
     * @param  mixed $slot Presentation slot
     * @return Student
     */
    public function nemjelentkezett($slot){
        return User::whereDoesntHave('events', function ($query) use ($slot) {
            $query->where('slot',$slot);
        })->get();
    }

    /**
     * fillUp a specific presentation
     *
     * @param  mixed $request
     * @return void
     */
    public function fillUpPresentation(Request $request) {
        Gate::authorize('e5n-admin');
        Event::find($request->input('presentation'))->fillUp();
    }

    public function fillUpAllPresentation() {
        Gate::authorize('e5n-admin');
        foreach (Event::all() as $presentation) {
            $presentation->fillUp();
        }
    }
}
