<?php

namespace App\Policies;

use App\Helpers\PermissionType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SettingPolicy
{
    use HandlesAuthorization;


    /**
     * settings can only be managed by operators
     */
    public function before(User $user)
    {
        return $user->hasPermission(PermissionType::Operator->value) ? Response::allow() : Response::denyAsNotFound();
    }
    /**
     * Determine whether the user can view any settings.
     * @return false
     */
    public function viewAny()
    {
        return false;
    }

    /**
     * Determine whether the user can set a setting.
     * @return false
     */
    public function set(User $user)
    {
        return $user->hasPermission(PermissionType::Admin->value);
    }

    /**
     * Determine whether the user can create settings.
     * @return false
     */
    public function create()
    {
        return false;
    }

    /**
     * Determine whether the user can delete the setting.
     * @return false
     */
    public function delete()
    {
        return false;
    }
}
