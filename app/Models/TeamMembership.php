<?php

namespace App\Models;

use App\Exceptions\NotAllowedException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Helpers\MembershipType;

/**
 * App\Models\TeamMembership
 * @property int $user_id
 * @property int $team_code
 * @property string $role
 */
class TeamMembership extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_memberships';

    protected $primaryKey = ['user_id', 'attendance_id'];

    public $incrementing = false;

    protected $fillable = ['role'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

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
