<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    public $timestamps = false;
    protected $table = 'presentations';
    protected $appends = ['occupancy'];


    public function getOccupancyAttribute(){
        $this->signups()->count();
    }
    public function signups(){
        return $this->hasMany(PresentationSignup::class);
    }
    public function students(){

        return $this->hasManyThrough(Student::class,PresentationSignup::class,'presentation_id','id','id','student_id');
    }
}
