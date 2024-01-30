<?php

namespace App\Policies;

use App\Helpers\PermissionType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BonusPointPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all permissions to admin users.
     */
    public function before(User $user)
    {
        if ($user->hasPermission(PermissionType::Admin->value) || $user->hasPermission(PermissionType::Operator->value)) {
            return true;
        }
    }
}
