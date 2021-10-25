<?php

namespace App;

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
        return $this->permissions()->where("permission","ADM");
    }


    public function currentEvent(){
        return $this->permissions()->whereHas('event',function($event){
            return $event->where('start','<',now())->where('end','>',now());
        })->event();
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

    public function rate(\App\Event $event, int $ratingValue){
        $rating = $this->ratings()->whereBelongsTo($event)->first();

        if($rating == null){
            $rating = new \App\Rating();
            $rating->event_id = $event->id;
            $rating->user_id = $this->id;
        }

        $rating->rating = $ratingValue;

        $rating->save();
    }

    public function ejg_class(){
        return $this->belongsTo(EJGClass::class);
    }

    public function signups(){
        return $this->hasMany(PresentationSignup::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function presentations(){
        return $this->hasManyThrough(Presentation::class,PresentationSignup::class,'student_id','id','id','presentation_id');
    }

    public function scores(){
        return $this->hasMany(Score::class);
    }

}
