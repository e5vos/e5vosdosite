<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Student extends Model
{
    public $timestamps = false;
    protected $table = 'students';

    protected $hidden = ['created_at','updated_at','laravel_through_key'];


    /**
     * Authenticates student with existing profile (from mailing list)
     * Creates new student if not in mailing list
     *
     * @param google_payload Google OAuth2 payload
     * @return \App\Student Authenticated student
     */
    public static function logIn($google_payload){
        $student =\App\Student::firstWhere("email",$google_payload["email"]);
        if($student == null){
            $student = new Student();
            $student->email = $google_payload["email"];
            $student->name = $google_payload["family_name"]." ".$google_payload["given_name"]; // name order fix
            $student->google_id = hash('sha256',$google_payload["sub"]);
            $student->save();
        }
        if($student->google_id == null){
            $student->google_id = hash('sha256',$google_payload["sub"]);
            $student->save();
        }
        return $student->google_id == hash('sha256',$google_payload["sub"]) ? $student : null;
    }

    public function isBusy($slot){
        return $this->presentations()->where("slot",$slot)->exists();
    }


    public function signUp(\App\Presentation $presentation){
        if(!$this->allowed){
            abort(403,"Diák nem jelentkezhet");
        }
        if($this->isBusy($presentation->slot)){
            abort(400, "Diák elfoglalt");
        }
        if($presentation->hasCapacity()){
            $signup = new \App\PresentationSignup();
            $signup->presentation_id = $presentation->id;
            $signup->student_id = $this->id;
            $signup->save();
        }else{
            abort(400, "Előadás betelt");
        }
    }

    public function ejg_class(){
        return $this->belongsTo(EJGClass::class);
    }

    public function signups(){
        return $this->hasMany(PresentationSignup::class);
    }

    public function presentations(){
        return $this->hasManyThrough(Presentation::class,PresentationSignup::class,'student_id','id','id','presentation_id');
    }

    public function scores(){
        return $this->hasMany(Score::class);
    }

}
