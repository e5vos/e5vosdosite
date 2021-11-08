<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasRelationships;

    protected $table = 'events';

    protected $dates = ['start','end'];

    protected $appends = ['occupancy', 'rating', 'orgaClasses', 'userRating'];

    protected $casts = ['start' => 'datetime:G:i', 'end' => 'datetime:G:i'];

    public function getOccupancyAttribute(){
        return $this->signups()->count();
    }

    public function getRatingAttribute(){
        return round($this->ratings()->pluck('value')->avg(), 1);
    }
    public function getOrgaClassesAttribute(){
        return $this->hasManyDeep(EJGClass::class, [Permission::class, User::class],['event_id','id','id'],['id','user_id','class_id'])->pluck('ejg_classes.name')->unique();
    }

    public function getUserRatingAttribute(){
        return Rating::where('user_id',  Auth::id())->where('event_id', $this->id)->pluck('value');
    }

    public function getOrgaCount(){
        return $this->permissions()->count();
    }

    public function presentations(){
        return Event::where('is_presentation',true);
    }

    public static function currentEvents(){
        return Event::where('start','<' ,now())->where('end','>',now())->get();
    }

    public function permissions(){
        return $this->hasMany(Permission::class);
    }

    public function organisers(){
        return $this->hasManyThrough(User::class,Permission::class,'event_id','id','id','user_id');
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
        $availalbeStudents = User::whereDoesntHave('events',function($query){
            $query->where('slot',$this->slot);
        })->limit($this->capacity - $this->occupancy)->get();
        foreach($availalbeStudents as $student){
            $student->signUp($this);
        }
    }

    public function hasCapacity(){
        return $this->capacity == null || $this->capacity-$this->occupancy > 0;
    }

    public function signups(){

        return $this->hasMany(EventSignup::class);
    }
    public function attendees(){
        return $this->hasManyThrough(Student::class,EventSignup::class,'event_id','id','id','student_id');
    }

    public function addOrganiser(User $user) {
        if (!$this->organisers()->get()->contains($user)){
            $user->permissions()->create([
                'event_id' => $this->id,
                'permission' => 'ORG',
            ]);
        }
    }

    public function removeOrganiser(User $user) {
        $this->permissions()->where('users_id', $user->id)->delete();
    }

}
