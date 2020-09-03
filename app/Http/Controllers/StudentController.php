<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\Student as StudentResource;


class StudentController extends Controller
{
    public function students(){
        Gate::authorize('admin');
        return StudentResource::collection(\App\Student::all());
    }

    public function student($code){
        Gate::authorize('admin');
        return \App\Student::firstWhere('code',$code);
    }

    public function setMagantanulo($code){
        Gate::authorize('admin');
        $student = student($code);
        $student->magantanulo = true;
        $student->save();
    }

    public function StudentAuth($diakcode,$omcode){
        if(Student::Auth($diakcode,$omcode)){
            $student = Student::firstWhere('code',$diakcode);
            $student->presentations;
            return $student;
        }
        else return false;
    }

    public function students(){
        Gate::authorize('admin');
        return StudentResource::collection(\App\Student::all());
    }

    public function student($code){
        Gate::authorize('admin');
        return \App\Student::firstWhere('code',$code);
    }
}
