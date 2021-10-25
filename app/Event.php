<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';

    protected $dates = ['start','end'];

    protected $appends = ['type','badgeClass','badgeMessage'];

    protected $casts = ['start' => 'datetime:G:i', 'end' => 'datetime:G:i'];

    public function getBadgeClassAttribute(){
        return 'success';
    }

    public function getBadgeMessageAttribute(){
        return $this->badgeClass;
    }

    /**
     * Get Event Type
     *
     * @return String
     */
    public function getTypeAttribute(){
        return ['Small','Medium','Large'][$this->weight-1];
    }

    public function getOrgaCountAttribute(){
        return $this->permissions()->count();
    }

    public function presentations(){
        return Event::where('is_presentation',true)->get;
    }

    public static function currentEvents(){
        return Event::where('start','<' ,now())->where('end','>',now())->get();
    }

    public function image(){
        return DBImage::find($this->image_id);
    }

    public function permissions(){
        return $this->hasMany(Permission::class);
    }

    public function scores(){
        return $this->hasMany(Score::class);
    }
    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function ongoing(){
        return $this->start < now() && now() < $this->end;
    }


    /**
     * Returns visitorcount of an event.
     *
     * @return int visitor count of an event
     */
    public function visitorcount(){
        return $this->scores()->where('place','0')->whereNotNull('student_id')->count();
    }


    //PRESENTATION related things from here

    public function fillUp(){
        if (!$this->is_presentation){
            abort(403,'Sajnos ezen az eseményen való részvételre nem lehet kötelezni a diákokat');
        }
        $availalbeStudents = Student::where('allowed', true)->get()->reject(function(Student $student){
            return $student->isBusy($this->slot);
        })->take($this->capacity - $this->occupancy);
        foreach($availalbeStudents as $student){
            $student->signUp($this);
        }
    }
    public function getOccupancyAttribute(){
        return $this->signups()->count();
    }

    public function hasCapacity(){
        return $this->capacity-$this->occupancy > 0;
    }

    public function signups(){

        return $this->hasMany(EventSignup::class);
    }
    public function students(){
        return $this->hasManyThrough(Student::class,EventSignup::class,'event_id','id','id','student_id');
    }

}
