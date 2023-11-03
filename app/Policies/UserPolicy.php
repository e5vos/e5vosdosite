<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Helpers\PermissionType;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all permissions to admin users.
     *
     * @param User $user
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
     *
     * @param User $user
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission(PermissionType::Teacher->value) || $user->hasPermission(PermissionType::TeacherAdmin->value);
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
     *
     * @param User $user
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
