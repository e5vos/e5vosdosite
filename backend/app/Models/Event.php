<?php

namespace App\Models;

use App\Exceptions\NotPresentationException;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Builder,
    SoftDeletes,
    Casts\Attribute,
    Relations\BelongsTo,
    Relations\HasMany,
    Relations\HasManyThrough,
};
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * App\Models\Event
 * @property int $id
 * @property int $slot_id
 * @property string $name
 * @property string $description
 * @property string $signup_type
 * @property string $organiser
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

    protected $table = 'events';

    protected $fillable = [
        'slot_id',
        'name',
        'description',
        'signup_type',
        'capacity',
        'img_url',
        'starts_at',
        'ends_at',
        'signup_deadline',
        'organiser',
    ];


    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'signup_deadline' => 'datetime',
    ];

    protected $appends = [
        'occupancy',
    ];

    protected $with = [
        'slot',
    ];

    public function occupancy() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->visitorcount(),
        )->shouldCache();
    }

    /**
     * Adds where filtering to start_date and end_date
     */
    public static function current(Builder $query): Builder
    {
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
    public function visitorcount()
    {
        return $this->attendances()->count();
    }

    /**
     * get all attendances of an event
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
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
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * get the organisers of the event
     */
    public function organisers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, Permission::class, 'event_id', 'id', 'id', 'user_id');
    }

    /**
     * get the ratings of an event
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * return all users participating in an event as individuals
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attendances', 'event_id', 'user_id', 'id', 'id');
    }

    /**
     * return all teams participating in an event
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'attendances', 'event_id', 'team_code', 'id', 'code');
    }

    /**
     * get all the users who attended the event
     */
    public function attendees(): Collection
    {
        return $this->signuppers()->where('attendances.is_present', true);
    }

    /**
     * Get all of the signups for the Event
     */
    public function signuppers(): Collection
    {
        return $this->users()->withPivot('is_present', 'rank')->get()->merge($this->teams()->withPivot('is_present', 'rank')->get());
    }


    public function fillUp()
    {
        if (!$this->slot()->where('slot_type', '!=', SlotType::presentation->value)->get()) {
            throw new NotPresentationException();
        }
        $availalbeStudents = User::whereDoesntHave('events', function($query){
            $query->where('slot',$this->slot);
        })->limit($this->capacity - $this->occupancy)->get();
        foreach ($availalbeStudents as $student) {
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


    /**
     * return if the even signup is open
     */
    public function isSignupOpen()
    {
        return $this->signup_type != null && $this->signup_deadline > now();
    }
}
