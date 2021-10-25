<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Presentation extends Model
{
    /**
     * @deprecated
     */
    public $timestamps = false;
    protected $table = 'presentations';
    protected $appends = ['occupancy'];

    use HasFactory;


    public function getOccupancyAttribute(){
        return $this->signups()->count();
    }

    public function fillUp(){

        $availalbeStudents = Student::where('allowed', true)->whereDoesntHave('presentations',function($query){
            $query->where('slot',$this->slot);
        })->limit($this->capacity - $this->occupancy)->get();
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
