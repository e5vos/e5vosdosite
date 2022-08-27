<?php

namespace App\Models;

use App\Exceptions\NotAllowedException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MembershipType;

/**
 * App\Models\TeamMembership
 * @property int $user_id
 * @property int $team_id
 * @property string $role
 */
class TeamMembership extends Model
{
    use HasFactory;

    protected $fillable = ['role'];

    /**
     * Get the user that owns the membership.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the team that has the membership.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function canPromote(TeamMembership $other): bool
    {
        if ($this->team_id != $other->team_id) {
            return false;
        } else if ($this->level === MembershipType::Invited) {
            return $this === $other;
        } else if ($this === $other) {
            return false;
        } else {
            return $this->role > $other->role;
        }
    }

    public function canDemote(TeamMembership $other): bool
    {
        if ($this->team_id != $other->team_id) {
            return false;
        } else if ($this === $other) {
            return $this->level !== MembershipType::Owner;
        } else {
            return $this->role < $other->role;
        }
    }


    /**
     * Promote other membership
     *
     * @param TeamMembership $other
     * @throws \Exception if the current membership can't promote this user
     * @return void
     */
    public function promote(TeamMembership $other): void
    {
        if (!$this->canPromote($other)) throw new NotAllowedException("You can't promote this user");
        switch ($other->role) {
            case MembershipType::Invited:
                $other->role = MembershipType::Member;
                break;
            case MembershipType::Member:
                $other->role = MembershipType::Leader;
                break;
            case MembershipType::Leader:
                $other->role = MembershipType::Owner;
                $this->role = MembershipType::Leader;
                break;
            default:
                $other->role = MembershipType::Invited;
        }
        $other->save();
        $this->save();
    }


    /**
     * Demote other membership
     *
     * @param TeamMembership $other
     * @throws \Exception if the current membership can't demote this user
     * @return void
     */
    public function demote(TeamMembership $other): void
    {
        if (!$this->canDemote($other)) {
            throw new NotAllowedException("You can't demote this user");
        }
        switch ($other->role) {
            case MembershipType::Invited:
                $other->delete();
                break;
            case MembershipType::Member:
                $other->delete();
                break;
            case MembershipType::Leader:
                $other->role = MembershipType::Member;
                break;
            default:
                $other->role = MembershipType::Invited;
        }
    }
}
