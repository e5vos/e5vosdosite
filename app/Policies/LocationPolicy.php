<?php

namespace App\Policies;

use App\Helpers\PermissionType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LocationPolicy
{
    use HandlesAuthorization;

    /**
     * Check if the user has permission for all actions.
     *
     * @return bool|null
     */
    public function before(User $user)
    {
        if ($user->hasPermission(PermissionType::Operator->value) || $user->hasPermission(PermissionType::Admin->value)) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @return Response|bool
     */
    public function create()
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
     */
    public function update()
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return Response|bool
     */
    public function delete()
    {
        return false;
    }
}
