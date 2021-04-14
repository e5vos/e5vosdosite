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
        Student::where('allowed', true)->limit($this->capacity-$this->occupancy)->get()->signUp($this);
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
