<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Presentation;
use App\PresentationSignup;
use App\Student;
use App\Http\Resources\Presentation as PresentationResource;

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

    public function attendanceSheet($code)
    {
        return PresentationResource::collection(
            PresentationSignup::
            join(
                'presentations', 'presentation_signups.presentation_id', '=', 'presentations.id'
            )->join(
                'students', 'presentation_signups.student_id', '=', 'students.id'
            )->select(
                'presentation_signups.id', 'students.class_id', 'students.name', 'presentation_signups.present', 'presentations.title'
            )->where(
                'presentations.code', '=', $code
            )->get()
        );
    }

    public function toggleAttendance($prezcode,$signup){
        Presentation::where('code',$prezcode)->first()->signups()->findOrFail($signup)->toggle();
    }




    public function nemjelentkezett($slot){
        //Gate::authorize('e5n-admin');
        return Student::where('magantanulo',false)->whereDoesntHave('presentations', function ($query) use ($slot) {
            $query->where('slot',$slot);
        })->get();
    }

}
