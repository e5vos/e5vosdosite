<?php

namespace App\Policies;

use App\Helpers\MembershipType;
use App\Models\TeamMemberShip;
use App\Models\User;
use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;

class TeamMemberShipPolicy
{
    use HandlesAuthorization;

    /**
     * Give admin access to everything
     */
    public function before(User $user)
    {
        return $user->hasPermission('ADM') || $user->hasPermission('OPT') ? true : null;
    }

    /**
     * Determine whether the user can view the models for the specific team.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Team|string $team = null) {
        return $user->isInTeam($team->code ?? $team ?? request()->teamCode);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Team|string $teamCode = null)
    {
        return $user->isLeaderOfTeam($teamCode->code ?? $teamCode ?? request()->teamCode);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TeamMemberShip  $teamMemberShip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        if (!request()->has('userCode') || !request()->has('promote')) {
            abort(400, 'Missing parameters');
        }
        $team = Cache::rememberForever('e5n.teams'.request()->teamCode, fn () => Team::find(request()->teamCode)->load('members'));
        if (!$team->members->pluck('e5code')->contains((request()->userCode))) {
            return false;
        }
        $updatableRole = $team->members->where('e5code', request()->userCode)->firstOrFail()->pivot->role;
        if (request()->promote === 'promote') {
            return (($updatableRole === MembershipType::Invited->value && request()->userCode === $user->e5code)
                || $updatableRole === MembershipType::Member->value && $user->isLeaderOfTeam($team->code));
        } elseif (request()->promote === 'demote') {
            return $updatableRole === MemberShipType::Leader->value && $user->isLeaderOfTeam($team->code) && $team->members->where('e5code', '!=', request()->userCode)->firstWhere('pivot.role', MembershipType::Leader->value) !== null;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TeamMemberShip  $teamMemberShip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        if (!request()->has('userCode')) {
            abort(400, 'Missing parameters');
        }
        $team = Cache::rememberForever('e5n.teams'.request()->teamCode, fn () => Team::with("members")->where('code', request()->teamCode)->firstOrFail());
        if (request()->userCode === $user->e5code) {
            return $user->isLeaderOfTeam($team->code) ? $team->members->where('id', '!=', request()->userCode)->first()->pivot->role === MembershipType::Leader->value : true;
        } else {
            return $user->isLeaderOfTeam($team->code);
        }
    }
}
