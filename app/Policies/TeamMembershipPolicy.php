<?php

namespace App\Policies;

use App\Helpers\MembershipType;
use App\Helpers\PermissionType;
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
        if ($user->hasPermission(PermissionType::Admin->value) || $user->hasPermission(PermissionType::Operator->value)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the models for the specific team.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Team|string $team = null)
    {
        return $user->isInTeam($team->code ?? $team ?? request()->teamCode);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  Team|string  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Team|string $team = null)
    {
        return $user->isLeaderOfTeam($team->code ?? $team ?? request()->team);
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
        if (!request()->has('userId') || !request()->has('promote')) {
            abort(400, 'Missing parameters');
        }
        $team = Cache::rememberForever('e5n.teams' . request()->teamCode, fn () => Team::find(request()->teamCode)->load('members', 'activity'));
        // if (!$team->members->pluck('id')->contains((request()->userId))) {
        //     return false;
        // }
        $updatableRole = $team->members->where('id', request()->userId)->first()?->pivot->role;
        $otherLeaderExists = $team->members->where('id', '!=', request()->userId)->firstWhere('pivot.role', MembershipType::Leader->value) !== null;
        $isLeader = $user->isLeaderOfTeam($team->code);
        if (request()->promote) {
            return ($updatableRole === MembershipType::Member->value && $isLeader)
                || ($updatableRole === MembershipType::Invited->value && $user->id === request()->userId)
                || ($updatableRole == null &&  $isLeader);
        } else {
            return ($user->id === request()->userId
                && ($updatableRole !== MembershipType::Leader->value || $otherLeaderExists)
            ) || ($updatableRole !== MembershipType::Leader->value && $isLeader);
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
        $team = Cache::rememberForever('e5n.teams' . request()->teamCode, fn () => Team::with("members")->where('code', request()->teamCode)->firstOrFail());
        if (request()->userCode === $user->e5code) {
            return $user->isLeaderOfTeam($team->code) ? $team->members->where('id', '!=', request()->userCode)->first()->pivot->role === MembershipType::Leader->value : true;
        } else {
            return $user->isLeaderOfTeam($team->code);
        }
    }
}
