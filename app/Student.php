<?php

namespace App;

use App\Exceptions\EventFullException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

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
        return $this->events()->where("slot",$slot)->exists();
    }


    /**
     * Sign up student to $event
     *
     * @param  \App\Event $event
     * @throws AuthorizationException if student is not allowed to sign up
     * @throws StudentBusyException if student is busy at the event timeslot
     * @throws EventFullException if the event is full
     * @return \App\EventSignup the newly created EventSignup object
     */
    public function signUp(\App\Event $event){

        if(!$this->allowed){
            throw new AuthorizationException("Student is not allowed to sign up");
        }

        if($event->slot != null && $this->isBusy($event->slot)){
            throw new \App\Exceptions\StudentBusyException("Student busy");
        }

        if(!$event->hasCapacity()){
            throw new EventFullException("Event full");
        }

        $signup = new \App\EventSignup();
        $signup->event_id = $event->id;
        $signup->student_id = $this->id;
        $signup->save();
        return $signup;
    }

    public function ejg_class(){
        return $this->belongsTo(EJGClass::class);
    }

    public function signups(){
        return $this->hasMany(EventSignup::class);
    }


    public function events(){
        return $this->hasManyThrough(Event::class,EventSignup::class,'student_id','id','id','event_id');
    }

    public function scores(){
        return $this->hasMany(Score::class);
    }

}
