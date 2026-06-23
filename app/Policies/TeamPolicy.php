<?php

namespace App\Policies;

use App\Helpers\PermissionType;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TeamPolicy
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
     * Determine whether the user can view any models.
     *
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission(PermissionType::Teacher->value) || $user->hasPermission(PermissionType::TeacherAdmin->value);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  Team  $team
     * @return Response|bool
     */
    public function view(User $user)
    {
        return $user->hasPermission(PermissionType::Teacher->value) || $user->hasPermission(PermissionType::TeacherAdmin->value) || $user->isInTeam(request()->teamCode);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return Response|bool
     */
    public function create()
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  Team  $team
     * @return Response|bool
     */
    public function update(User $user)
    {
        return $user->isLeaderOfTeam(request()->teamCode) || $user->hasPermission(PermissionType::TeacherAdmin->value);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  Team  $team
     * @return Response|bool
     */
    public function delete(User $user)
    {
        return $user->isLeaderOfTeam(request()->teamCode);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return Response|bool
     */
    public function restore(User $user)
    {
        return $user->isLeaderOfTeam(request()->teamCode);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return Response|bool
     */
    public function forceDelete()
    {
        return false;
    }
}
