<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use App\Http\Resources\Student as StudentResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StudentController extends Controller
{
    /**
     * Get all students
     * @return \Illuminate\Http\Resources\Json\StudentResourceCollection
     */
    public function students(){
        Gate::authorize('admin');
        return StudentResource::collection(\App\Student::all());
    }


    /**
     * Set Magantanulo status
     *
     * @param  Student $student
     * @return void
     */
    public function setMagantanulo($student){
        Gate::authorize('admin');
        $student->magantanulo = true;
        $student->save();
    }

    public function StudentAuth(Request $request){
        $id_token = $request->input("id_token");

        $client = new \Google_Client([
            'client_id' => env("GOOGLE_CLIENT_ID"),
        ]);
        try{
            $payload = $client->verifyIdToken($id_token);
            if($payload){
                // $domain = $payload["hd"]
                $student = \App\Student::logIn($payload);
                if($student!=null){
                    $request->session()->put("student_id",$student->id);
                }
            }else{
                abort(403);
            }
        }catch(\Exception $e){
            abort(400);
        }

    }

}
