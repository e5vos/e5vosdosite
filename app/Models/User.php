<?php

namespace App\Models;

use App\Exceptions\EventFullException;
use App\Exceptions\StudentBusyException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','google_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function permissions(){
        return $this->hasMany(Permission::class);
    }

    public function isAdmin(){
        return $this->permissions()->where("permission","ADM")->exists();
    }


    public function currentEvent(){
        return $this->permissions()->whereHas('event',function($event){
            return $event->where('start','<',now())->where('end','>',now());
        })->event();
    }


    public function isBusy($slot){
        return $this->events()->where("slot",$slot)->exists();
    }



    /**
     * Sign up user to $event
     *
     * @param  Event $event
     * @throws StudentBusyException if user is busy at the event timeslot
     * @throws EventFullException if the event is full
     * @return EventSignup the newly created EventSignup object
     */
    public function signUp(Event $event){
        if($event->slot !=null && $this->isBusy($event->slot)){
            throw new StudentBusyException();
        }
        if(!$event->hasCapacity()){
            throw new EventFullException();
        }
        $signup = new EventSignup();
        $signup->event()->associate($event);
        $signup->user()->associate($this);
        $signup->save();
        return $signup;
    }

    /**
     * Rate an event
     *
     * @param  Event $event
     * @param  int $ratingValue
     * @return \App\Rating
     */
    public function rate(Event $event, int $ratingValue){
        $rating = $this->ratings()->whereBelongsTo($event)->first();

        if($rating == null){
            $rating = $this->ratings()->create([
                'event_id'=>$event->id,
                'user_id'=>$this->id,
                'value' => $ratingValue
            ]);
        }else{
            $rating->value = $ratingValue;
            $rating->save();
        }

        return $rating;
    }

    public function ejg_class(){
        return $this->belongsTo(EJGClass::class, 'class_id');
    }
    public function ejgClass(){
        return $this->belongsTo(EJGClass::class, 'class_id');
    }

    public function signups(){
        return $this->hasMany(EventSignup::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function events(){
        return $this->hasManyThrough(Event::class,EventSignup::class,'user_id','id','id','event_id');
    }

    public function presentations(){
        return $this->events()->where("is_presentation",true);
    }

    public function scores(){
        return $this->hasMany(Score::class);
    }

    public function teamMemberships(){
        return $this->hasMany(TeamMember::class);
    }

    public function teams(){
        return $this->hasManyThrough(Team::class,TeamMember::class,'user_id','id','id','team_id');
    }

    public function managedTeams(){
        return $this->teams()->where('is_manager',true);
    }


}
