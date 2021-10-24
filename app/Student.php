<?php

namespace App;

use App\Exceptions\PresentationFullException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Student extends Authenticatable
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'students';

    protected $hidden = ['created_at','updated_at','laravel_through_key'];


    /**
     * Authenticates student with existing profile (from mailing list)
     * Creates new student if not in mailing list
     *
     * @param array Google OAuth2 payload
     * @return \App\Student Authenticated student
     * @deprecated
     */
    public static function logIn(array $google_payload){
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





    /**
     * Returns whether the student is busy at the specified slot
     *
     * @param  int $slot
     * @return bool
     */
    public function isBusy($slot){
        return $this->presentations()->where("slot",$slot)->exists();
    }


    /**
     * Sign up student to $presentation
     *
     * @param  \App\Presentation $presentation
     * @throws AuthorizationException if student is not allowed to sign up
     * @throws StudentBusyException if student is busy at the presentations timeslot
     * @throws PresentationFullException if the presentation is full
     * @return \App\PresentationSignup the newly created PresentationSignup object
     */
    public function signUp(\App\Presentation $presentation){

        if(!$this->allowed){
            throw new AuthorizationException("Student is not allowed to sign up");
        }

        if($this->isBusy($presentation->slot)){
            throw new \App\Exceptions\StudentBusyException("Student busy");
        }

        if(!$presentation->hasCapacity()){
            throw new PresentationFullException("Presentation full");
        }

        $signup = new \App\PresentationSignup();
        $signup->presentation_id = $presentation->id;
        $signup->student_id = $this->id;
        $signup->save();
        return $signup;
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
