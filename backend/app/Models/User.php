<?php

namespace App\Models;

use App\Exceptions\EventFullException;
use App\Exceptions\StudentBusyException;
use App\Helpers\PermissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
/**
 * App\Models\User
 * @property int $id
 * @property string $name
 * @property string $e5mail
 * @property string $google_id
 * @property string $e5code
 * @property string $ejg_class
 * @property string|null $img_url
 */
class User extends Authenticable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'img_url',
        'name',
        'email',
        'google_id',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'google_id',
    ];

    /**
     * Get all of the permissions for the User
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Determine if the user has the $code permission
     * @param string $code
     *
     * @return boolean
     */
    public function hasPermission(string $code)
    {
        return $this->permissions()->where('code', '=', $code)->exists();
    }

    /**
     * Get all of the Events oranised by the User
     */
    public function organisedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'permissions', 'user_id', 'event_id')->where('code', '=', PermissionType::Organiser);
    }

    /**
     * determine if the user is an organiser of the $event
     *
     * @param int $eventId
     *
     * @return boolean
     */
    public function organisesEvent(int $eventId): bool
    {
        return $this->organisedEvents()->find($eventId) != null;
    }

    /**
     * The teams that the User is part of
     */
    public function teams(): HasManyThrough
    {
        return $this->hasManyThrough(Team::class, TeamMember::class);
    }
    /**
     * The teammemberships for the User
     */
    public function teamMemberships(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }


    /**
     * Get all attendances of the user
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
    public function events(){
        return $this->hasManyThrough(Event::class,Attendance::class,'user_id','id','id','event_id');
    }

    /*
    * Get all attended events of the user that are presentations
    */
    public function presentations(): HasManyThrough
    {
        return $this->attendances()->event()->where(function($q){
            $q->join('slots', 'event.slot_id', '=', 'slot.id')->where('slot.is_presentation', '=', true);
        });
    }

    public function managedEvents(){
        return $this->permissions()->events();
    }
    /**
     * Get all ratings done by the user
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
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
        $signup = new Attendance();
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

}
