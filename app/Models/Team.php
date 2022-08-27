<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MembershipType;

/**
 * App\Models\Team
 * @property int $id
 * @property string $name
 * @property string $code
 */
class Team extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';

    protected $fillable = ['name'];


    /**
     * Get all of the members for the Team
     */
    public function members(): HasMany
    {
        return $this->hasMany(TeamMembership::class);
    }

    /*
    * get all attendances for the team
    */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
    /*
    * get all users that attend a specific event for the team
    */
    public function usersAttendingEvent(Event $event): BelongsToMany
    {
        return $this->attendances()->where('event_id',$event->id)->userInTeam();
    }

    /**
     * Create a new membership in the team for $user
     * @param User $user the membership to create
     * @param MembershipType $membershipType (optional) the type of membership to create (default: Invited)
     * @return TeamMembership newly created membership
     */
    public function addMember(User $user, $role=MembershipType::Invited){
        $teamMember = new TeamMembership();
        $teamMember->team()->associate($this);
        $teamMember->user()->associate($user);
        $teamMember->role=$role;
        $teamMember->save();
        return $teamMember;
    }
}
