<?php

namespace App\Models;

use App\Exceptions\NotPresentationException;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use Astrotomic\CachableAttributes\CachableAttributes;
use Astrotomic\CachableAttributes\CachesAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * App\Models\Event
 *
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
 * @property int|null $root_parent
 * @property int|null $direct_child
 */
class Event extends Model implements CachableAttributes
{
    use CachesAttributes, HasFactory, SoftDeletes;

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

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'signup_deadline' => 'datetime',
    ];

    protected $cachableAttributes = [
        'occupancy',
    ];

    public function getOccupancyAttribute(): int
    {
        return $this->rememberForever('occupancy', fn () => $this->attendanceCount());
    }

    /**
     * Adds where filtering to start_date and end_date
     */
    public static function current(Builder $query, $time = null): Builder
    {
        $time ??= now();

        return $query->where('starts_at', '<=', $time)->where('ends_at', '>=', $time);
    }

    /**
     * Get currently running events
     */
    public static function currentEvents($time = null)
    {
        $time ??= now();

        return Event::where(fn ($query) => Event::current($query, $time));
    }

    /**
     * Returns visitorcount of an event.
     *
     * @return int visitor count of an event
     */
    public function attendanceCount(): int
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
        return $this->belongsToMany(User::class, 'permissions', 'event_id', 'user_id')
            ->where('permissions.code', PermissionType::Organiser->value)
            ->orWhere('permissions.code', PermissionType::Scanner->value);
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
        return $this->belongsToMany(User::class, 'attendances', 'event_id', 'user_id', 'id', 'id')->withPivot('is_present', 'rank');
    }

    /**
     * return all teams participating in an event
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'attendances', 'event_id', 'team_code', 'id', 'code')->withPivot('is_present', 'rank');
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

    /**
     * Get all of the signuppers who scored in the Event
     */
    public function scored(): Collection
    {
        return $this->signuppers()->whereIsNotNull('rank')->orderBy('rank', 'asc');
    }

    public function fillUp()
    {
        if (! $this->slot()->where('slot_type', '!=', SlotType::presentation->value)->get()) {
            throw new NotPresentationException;
        }
        $availalbeStudents = User::whereDoesntHave('events', function ($query) {
            $query->where('slot', $this->slot);
        })->limit($this->capacity - $this->occupancy)->get();
        foreach ($availalbeStudents as $student) {
            $student->signUp($this);
        }
    }

    public function addOrganiser(User $user)
    {
        if (! $this->organisers()->get()->contains($user)) {
            $user->permissions()->create([
                'event_id' => $this->id,
                'permission' => PermissionType::Organiser,
            ]);
        }
    }

    public function removeOrganiser(User $user)
    {
        $this->permissions()->where('users_id', $user->id)->delete();
    }

    /**
     * return if the even signup is open
     */
    public function isSignupOpen()
    {
        return $this->signup_type != null && ($this->signup_deadline?->isFuture() ?? true);
    }

    /**
     * return if the event is running
     */
    public function isRunning()
    {
        return $this->starts_at->isPast() && $this->ends_at->isFuture();
    }
}
