<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\MembershipType;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\EventFullException;
use App\Exceptions\AlreadySignedUpException;

/**
 * App\Models\Team
 * @property string $name
 * @property string $code
 */
class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teams';

    protected $primaryKey = 'code';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['code', 'name'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'code' => 'string',
    ];


    /**
     * Get all of the members for the Team
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_memberships', 'team_code', 'user_id')->withPivot('role');
    }

    /**
     * get all team memberships
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(TeamMembership::class, 'team_code', 'code');
    }

    /*
    * get all attendances for the team
    */
    public function signups(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function activity(): HasMany
    {
        return $this->signups()->with('event:name,id,location', 'teamMemberAttendance');
    }

    /**
     * Get all signups where the team was present
     */
    public function attendances(): HasMany
    {
        return $this->signups()->where('is_present', true);
    }

    /**
     * get all evetns the team has signed up for
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'attendances', 'team_code', 'event_id');
    }


    /*
    * get all users that attend a specific event for the team
    */
    public function usersAttendingEvent(Event $event): BelongsToMany
    {
        return $this->attendances()->where('event_id', $event->id)->userInTeam();
    }

    /**
     * Create a new membership in the team for $user
     * @param User $user the membership to create
     * @param MembershipType $membershipType (optional) the type of membership to create (default: Invited)
     * @return TeamMembership newly created membership
     */
    public function addMember(User $user, $role = MembershipType::Invited)
    {
        $teamMember = new TeamMembership();
        $teamMember->team()->associate($this);
        $teamMember->user()->associate($user);
        $teamMember->role = $role;
        $teamMember->save();
        return $teamMember;
    }

    /**
     * Sign up user to $event
     *
     * @param  Event $event
     * @param  bool $force whether to force the signup even if it has a root parent
     * @throws EventFullException if the event is full
     * @throws AlreadySignedUpException if the user is already signed up
     * @throws SignupNotRequiredException if the event does not require signups
     * @return Attendance the newly created attendance
     */
    public function signUp(Event $event, bool $force = false)
    {
        if (!$force && $event->root_parent !== null) {
            $this->signUp(Event::findOrFail($event->root_parent));
            return $this->signups()->where('event_id', $event->id)->first();
        }
        if (isset($event->capacity) && $event->occupancy >= $event->capacity) {
            throw new EventFullException();
        }
        if ($this->signups()->where('event_id', $event->id)->exists()) {
            throw new AlreadySignedUpException();
        }
        if ($event->direct_child !== null) {
            $this->signUp(Event::findOrFail($event->direct_child), true);
        }
        $signup = new Attendance();
        $signup->event()->associate($event);
        $signup->team()->associate($this);
        $signup->save();

        $members = $this->members()->get(['id AS user_id'])->toArray();
        $signup->teamMemberAttendances()->createMany($members);

        $event->forget('occupancy');
        return $signup;
    }

    /**
     * make team attend $event
     *
     * @param  Event $event the event to attend
     * @param  bool $force whether to force the signup even if it has a root parent
     * @throws EventFullException if the event is full
     * @return Attendance the newly created EventSignup object
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
        $event->forget('occupancy');
        return $signup->load('teamMemberAttendances.user');
    }
}
