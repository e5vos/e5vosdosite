<?php

namespace App\Models;

use App\Exceptions\AlreadySignedUpException;
use App\Exceptions\EventFullException;
use App\Exceptions\StudentBusyException;
use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use App\Models\TeamMembership;

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
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'users';

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
        'e5code',
        'created_at',
        'updated_at',
    ];

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    /**
     * Boot the model.
     *
     * Assing global scopes, etc. Order by ejg_class and name
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', fn ($builder) => $builder->orderBy('ejg_class')->orderBy('name'));
    }

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
        $permissions = $_SESSION['permissions'] ?? Cache::remember('users.' . $this->id . '.permissions', now()->addMinutes(5), function () {
            return $this->permissions()->get()->pluck('code')->toArray();
        });
        $_SESSION['permissions'] = $permissions;
        return in_array($code, $permissions) || in_array(PermissionType::Operator->value, $permissions);
    }


    /**
     * Get the organised events for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organisedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'permissions', 'user_id', 'event_id')->where('code', '=', PermissionType::Organiser);
    }

    /**
     * All th events where the user is a scanner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scannerAtEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'permissions', 'user_id', 'event_id')->where('code', '=', PermissionType::Scanner);
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
     * determine if the user is a scanner of the $event
     *
     * @param int $eventId
     *
     * @return boolean
     */
    public function scansEvent(int $eventId): bool
    {
        return $this->scannerAtEvents()->find($eventId) != null;
    }

    /**
     * The teams that the User is part of
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_memberships', 'user_id', 'team_code');
    }
    /**
     * The teammemberships for the User
     */
    public function teamMemberships(): HasMany
    {
        return $this->hasMany(TeamMemberShip::class);
    }


    /**
     * Get all attendances of the user
     */
    public function signups(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all attendances of the user where the user was present
     */
    public function attendances(): HasMany
    {
        return $this->signups()->where('is_present', true);
    }

    /**
     * Get all events the user has signed up for
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'attendances');
    }

    /*
    * Get all attended events of the user that are presentations
    */
    public function presentations()
    {
        return $this->events()->join('slots', 'events.slot_id', '=', 'slots.id')
            ->where('slots.slot_type', SlotType::presentation);
    }

    /**
     * Get all ratings done by the user
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }


    /**
     * Get all teamattendances of the user
     */
    public function teamSignups(): BelongsToMany
    {
        return $this->belongsToMany(Attendance::class, 'team_member_attendances', 'user_id', 'attendance_id');
    }

    /**
     * Get all events the user has signed up for WITHOUT a team
     */
    public function userActivity(): HasMany
    {
        return $this->signups()->with(['event:name,id,location_id']);
    }

    /**
     * Get all events the user has signed up for WITH a team
     */
    public function teamActivity(): BelongsToMany
    {
        return $this->teamSignups()->with([
            'event:name,id,location_id',
            'team:name,code',
            'team.members:id,name,ejg_class',
            'teamMemberAttendances:user_id,is_present,attendance_id'
        ]);
    }

    /**
     * Get all events the user has signed up for
     */
    public function activity()
    {
        return $this->userActivity()->union($this->teamActivity()->getQuery());
    }

    /**
     * Determine if the user has an attendance in the slot
     */
    public function isBusyInSlot(Slot|int $slot): bool
    {
        return $this->events()->where('slot_id', $slot->id ?? $slot)->count() > 0;
    }

    public function isBusy($time = null): bool
    {
        $time ??= now();
        return Attendance::whereIn("event_id", Event::currentEvents($time)->pluck("id")->toArray())
            ->where("user_id", $this->id)
            ->orWhereIn("team_code", $this->teamMemberships()->pluck("team_code")->toArray())
            ->count() > 0;
    }

    /**
     * Sign up user to $event
     *
     * @param  Event $event the event to sign up for
     * @param  bool $force whether to force the signup even if it has a root parent
     * @throws StudentBusyException if user is busy at the event timeslot
     * @throws EventFullException if the event is full
     * @throws AlreadySignedUpException Student is signed up for this event
     * @return Attendance the newly created EventSignup object
     */
    public function signUp(Event $event, bool $force = false)
    {
        if (!$force && $event->root_parent !== null) {
            $this->signUp(Event::findOrFail($event->root_parent));
            return Attendance::where('user_id', $this->id)->where('event_id', $event->id)->first();
        }
        if ($event->slot !== null && $event->slot->slot_type == SlotType::presentation && $this->isBusyInSlot($event->slot)) {
            throw new StudentBusyException();
        }
        if (
            isset($event->capacity) && $event->occupancy >= $event->capacity  &&
            !request()->user()->hasPermission(PermissionType::Admin->value)
        ) {
            throw new EventFullException();
        }
        if (Attendance::where('user_id', $this->id)->where('event_id', $event->id)->exists()) {
            throw new AlreadySignedUpException();
        }
        if ($event->direct_child !== null) {
            $this->signUp(Event::findOrFail($event->direct_child), true);
        }
        $signup = new Attendance();
        $signup->event()->associate($event);
        $signup->user()->associate($this);
        $signup->save();
        $event->forget('occupancy');
        return $signup;
    }

    /**
     * make user attend $event
     *
     * @param  Event $event the event to attend
     * @param  bool $force whether to force the signup even if it has a root parent
     * @return Attendance the newly created Attendance object
     */
    public function attend(Event $event, bool $force = false)
    {
        if (!$force && $event->root_parent !== null) {
            $this->attend(Event::findOrFail($event->root_parent));
            return $this->signups()->where('event_id', $event->id)->first();
        }
        $signup = $this->signups()->where('event_id', $event->id)->first();
        if (!isset($signup)) {
            if (isset($event->capacity) && $event->occupancy >= $event->capacity) {
                throw new EventFullException();
            }
            $signup = new Attendance();
            $signup->event()->associate($event);
            $signup->user()->associate($this);
        }
        if ($event->direct_child !== null) {
            $this->attend(Event::findOrFail($event->direct_child), true);
        }
        if (isset(request()->present)) {
            $signup->is_present = request()->present;
        } else {
            $signup->togglePresent();
        }
        $signup->save();
        $event->forget('occupancy');
        return $signup->load('user');
    }

    /**
     * Determine if the user is in the specified m
     *
     * @param  Team|string $team
     * @return boolean
     */
    public function isInTeam(Team | string $team)
    {
        return $this->teamMemberships()->where('team_code', $team->code ?? $team)->where('role', '!=', MembershipType::Invited)->exists();
    }

    /**
     * Determine if the user is the leader of the specified team
     *
     * @param  Team|string $team
     * @return boolean
     */
    public function isLeaderOfTeam(Team | string $team)
    {
        return $this->teamMemberships()->where('team_code', $team->code ?? $team)->where('role', MembershipType::Leader)->exists();
    }
}
