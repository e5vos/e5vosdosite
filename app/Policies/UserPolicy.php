<?php

namespace App\Policies;

use App\Helpers\PermissionType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all permissions to admin users.
     */
    public function before(User $user)
    {
        if ($user->hasPermission(PermissionType::Operator->value) || $user->hasPermission(PermissionType::Admin->value)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user)
    {
        return $user->hasPermission(PermissionType::Teacher->value) || $user->hasPermission(PermissionType::TeacherAdmin->value);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create()
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model)
    {
        $modelId = $model?->id ?? request()->userId;

        return $user->id === $modelId;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete()
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore()
    {
        return false;
    }

    /**
     * Determine whether the user can search in the models.
     */
    public function search()
    {
        return true;
    }
}
