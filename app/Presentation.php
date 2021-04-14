<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    public $timestamps = false;
    protected $table = 'presentations';
    protected $appends = ['occupancy'];

    public function getOccupancyAttribute(){
        return $this->signups()->count();
    }

    public function fillUp(){
        $availalbeStudents = Student::where('allowed', true)->get()->reject(function(Student $student){
            return $student->isBusy($this->slot);
        })->take($this->capacity - $this->occupancy);
        foreach($availalbeStudents as $student){
            $student->signUp($this);
        }

    }

    public function hasCapacity(){
        return $this->capacity-$this->occupancy > 0;
    }


    public function signups(){
        return $this->hasMany(PresentationSignup::class);
    }
    public function students(){
        return $this->hasManyThrough(Student::class,PresentationSignup::class,'presentation_id','id','id','student_id');
    }



}
