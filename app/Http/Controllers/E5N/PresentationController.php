<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Presentation;
use App\PresentationSignup;
use App\Student;
use App\Http\Resources\Presentation as PresentationResource;
use Egulias\EmailValidator\Exception\CharNotAllowed;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function Symfony\Component\String\b;

class PresentationController extends Controller
{
    /**
     * returns the presentations signup view
     *
     * @return view
     */
    public function presentationsSignup() {
        return view('e5n.presentations.signup');
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
     * Display a listing of presentations based on timeslot.
     *
     * @return \Illuminate\Http\Response
     */
    public function slot($slot)
    {
        return PresentationResource::collection(Presentation::where('slot',$slot)->get()->reject(function($presentation){
            return $presentation->occupancy >= $presentation->capacity; // no free capacity
            // kiszervezhető kliensre teljesítmény növelés céljából
        }));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('e5n-presentation-create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Presentation::find($id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('e5n-presentation-delete');
        return Presentation::where('id',$id)->delete();
    }

    /**
     * Get attendnce sheet of presentation
     *
     * @param  string $code Code of Presentation
     * @return void
     */
    public function attendanceSheet($code){
        $presentation = Presentation::firstWhere('code',$code);

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
        Presentation::where('code',$prezcode)->first()->signups()->findOrFail($signup)->toggle();
    }


    /**
     * signs a student up to a presentation
     *
     * @param  mixed $request {}
     * @return void
     */
    public function signUp(Request $request){
        $student_id = $request->session()->get("student_id");
        if($student_id == null){
            abort(403, "Student not authenticated");
        }
        $student = \App\Student::find($student_id);
        $presentation = \App\Presentation::find($request->input("presentation"));
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
        $student = \App\Student::find($student_id);
        $student->signups()->where("presentation_id",$request->input("presentation"))->delete();
    }

    /**
     * Returns the selected presentation of a given student
     *
     * @param  mixed $request {}
     * @return void
     */
    public function getSelectedPresentations(Request $request){
        $student_id = $request->session()->get("student_id");
        if($student_id == null){
            abort(403, "Student not authenticated");
        }
        $student = \App\Student::find($student_id);
        return response()->json(
            $student->presentations()->get()
        );
    }


    /**
     * Returns students who didn't sign up to any presentation in $slot
     *
     * @param  mixed $slot Presentation slot
     * @return Student
     */
    public function nemjelentkezett($slot){
        //Gate::authorize('e5n-admin');
        return Student::where('magantanulo',false)->whereDoesntHave('presentations', function ($query) use ($slot) {
            $query->where('slot',$slot);
        })->get();
    }

    /**
     * fillUp a specific pres
     *
     * @param  mixed $request
     * @return void
     */
    public function fillUpPresentation(Request $request) {
        //Gate::authorize('e5n-admin');
        return Presentation::find($request->input('presentation'))->fillUp();
    }

    public function fillUpAllPresentation(Request $request) {
        //Gate::authorize('e5n-admin');
        return Presentation::all()->fillUp();
    }
}
