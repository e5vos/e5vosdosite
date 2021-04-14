<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Presentation;
use App\PresentationSignup;
use App\Student;
use App\Http\Resources\Presentation as PresentationResource;
use Illuminate\Support\Facades\Gate;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PresentationController extends Controller
{
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


    public function signUp(Request $request){
        Gate::authorize("e5n-presentationSignup");
        $student_id = $request->session()->get("student_id");
        if($student_id == null){
            abort(403, "Student not authenticated");
        }
        $student = \App\Student::find($student_id);
        $presentation = \App\Presentation::find($request->input("presentation"));
        $student->signUp($presentation);
    }

    public function deleteSignUp(Request $request){
        $student_id = $request->session()->get("student_id");
        if($student_id == null){
            abort(403, "Student not authenticated");
        }
        $student = \App\Student::find($student_id);
        $student->signups()->where("presentation_id",$request->input("presentation"))->delete();
    }

    public function getSelectedPresentations(Request $request){
        $student_id = $request->session()->get("student_id");
        if($student_id == null){
            abort(403, "Student not authenticated");
        }
        $student = \App\Student::find($student_id);
        if($student->doesntExist()){
            abort(403, "Student does not exist");
        }
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

}
