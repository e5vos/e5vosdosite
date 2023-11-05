<?php

namespace App\Policies;

use App\Helpers\PermissionType;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all permissions to admin users.
     */
    public function before(User $user)
    {
        if ($user->hasPermission(PermissionType::Operator->value)) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $permission = json_decode(request()->permission);
        switch ($permission->code) {
            case PermissionType::Scanner->value:
                return $user->hasPermission(PermissionType::Admin->value) || $user->organisesEvent($permission->eventId);
            case PermissionType::Organiser->value:
                return $user->hasPermission(PermissionType::Admin->value);
            case PermissionType::Admin->value:
            case PermissionType::Student->value:
            case PermissionType::Teacher->value:
            case PermissionType::TeacherAdmin->value:
            case PermissionType::Operator->value:
                return false;
            default:
                abort(400, 'Invalid permission code');
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function destroy(User $user, Permission $permission)
    {
        $permission ??= json_decode(request()->permission);
        switch ($permission->code) {
            case PermissionType::Scanner->value:
                return $user->hasPermission(PermissionType::Admin->value) || $user->organisesEvent($permission->eventId);
            case PermissionType::Organiser->value:
                return $user->hasPermission(PermissionType::Admin->value);
            case PermissionType::Admin->value:
            case PermissionType::Student->value:
            case PermissionType::Teacher->value:
            case PermissionType::TeacherAdmin->value:
            case PermissionType::Operator->value:
                return false;
            default:
                abort(400, 'Invalid permission code');
        }
    }
}
