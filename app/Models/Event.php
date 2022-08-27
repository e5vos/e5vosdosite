<?php

namespace App\Models;

use App\Exceptions\NotPresentationException;
use App\Helpers\PermissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Event
 * @property int $id
 * @property int $slot_id
 * @property string $name
 * @property string $description
 * @property string $signup_type
 * @property int $capacity
 * @property int $occupancy
 * @property float $rating
 * @property int $orgaCount
 * @property string|null $img_url
 * @property \Illuminate\Support\Carbon|null $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Event extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $dates = ['start','end'];
    protected $casts = ['start' => 'datetime:G:i', 'end' => 'datetime:G:i'];


    public function occupancy() : Attribute{
        return Attribute::make(
            get: fn($value) => $value->signups()->count()
        )->shouldCache();
    }

    public function rating() : Attribute{
        return Attribute::make(
            get: fn($value) => round($value->ratings()->avg('rating'),0)
        )->shouldCache();
    }

    public function orgaCount() : Attribute{
        return Attribute::make(
            get: fn($value) => $value->permissions()->count()
        )->shouldCache();
    }

    /**
     * Adds where filtering to start_date and end_date
     */
    public static function current(Builder $query): Builder{
        return $query->where('start_date', '<=', now())->where('end_date', '>=', now());
    }

    /**
     * Get currently running events
     *
     */
    public static function currentEvents(){
        return Event::where(fn ($query) => Event::current($query));
    }

    /**
     * Returns visitorcount of an event.
     *
     * @return int visitor count of an event
     */
    public function visitorcount(){
        return $this->present()->count();
    }

    public function present(){
        return $this->attendances()->where("attendance.is_present","=",true);
    }



    /**
     * Get the slot that has the Event
     */
    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }


    /**
     * Get the location of an event
     */
    public function location(){
        return $this->belongsTo(Location::class);
    }
    /*
     * Get the attendeances of an event
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * get the permissions of an event
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * get the ratings of an event
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Determine if the event is a presentation
     *
     * @return boolean
     */
    public function is_presentation()
    {
        return $this->slot()->get('is_presentation');
    }

    public function participants(): Collection{
        return $this->attendances()->user()->merge($this->attendances()->usersInTeam())->unique();
    }


    public function fillUp(){
        if (!$this->slot()->get('is_presentation')){
            throw new NotPresentationException();
        }
        $availalbeStudents = User::whereDoesntHave('events',function($query){
            $query->where('slot',$this->slot);
        })->limit($this->capacity - $this->occupancy)->get();
        foreach($availalbeStudents as $student){
            $student->signUp($this);
        }
    }

    public function addOrganiser(User $user) {
        if (!$this->organisers()->get()->contains($user)){
            $user->permissions()->create([
                'event_id' => $this->id,
                'permission' => PermissionType::Organiser,
            ]);
        }
    }

    public function removeOrganiser(User $user) {
        $this->permissions()->where('users_id', $user->id)->delete();
    }




}
