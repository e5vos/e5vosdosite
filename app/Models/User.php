<?php

namespace App\Models;

use App\Exceptions\AlreadySignedUpException;
use App\Exceptions\EventFullException;
use App\Exceptions\StudentBusyException;
use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

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
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

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
        $permissions = Cache::remember('users.' . $this->id . '.permissions', now()->addMinutes(5), function () {
            return $this->permissions()->get()->pluck('code')->toArray();
        });
        return in_array($code, $permissions) || in_array(PermissionType::Operator->value, $permissions);
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
        return $this->belongsToMany(Event::class, 'attendances', 'user_id', 'event_id');
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
     * Determine if the user has an attendance in the slot
     */
    public function isBusy(Slot|int $slot): bool
    {
        return $this->events()->where('slot_id', $slot->id ?? $slot)->count() > 0;
    }

    /**
     * Sign up user to $event
     *
     * @param  Event $event the event to sign up for
     * @param  bool $force whether to force the signup even if it has a root parent
     * @throws StudentBusyException if user is busy at the event timeslot
     * @throws EventFullException if the event is full
     * @throws AlreadySignedUpException Student is signed up for this event
     * @return EventSignup the newly created EventSignup object
     */
    public function signUp(Event $event, bool $force = false)
    {
        if (!$force && $event->root_parent !== null) {
            $this->signUp(Event::findOrFail($event->root_parent));
            return Attendance::where('user_id', $this->id)->where('event_id', $event->id)->first();
        }
        if ($event->slot !== null && $event->slot->slot_type == SlotType::presentation && $this->isBusy($event->slot)) {
            throw new StudentBusyException();
        }
        if (
            isset($event->capacity) && $event->occupancy >= $event->capacity  &&
            !request()->user()->hasPermission(PermissionType::Aadmin->value)
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
        return $signup;
    }

    /**
     * make user attend $event
     *
     * @param  Event $event the event to attend
     * @param  bool $force whether to force the signup even if it has a root parent
     * @return EventSignup the newly created EventSignup object
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
            $signup->team()->associate($this);
        }
        if ($event->direct_child !== null) {
            $this->attend(Event::findOrFail($event->direct_child), true);
        }

        $signup->togglePresent();
        $signup->save();
        return $signup;
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
