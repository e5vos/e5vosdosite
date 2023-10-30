<?php

namespace App\Policies;

use App\Models\BonusPoint;
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
        if ($user->hasPermissionTo('ADM') || $user->hasPermissionTo('OPT')) {
            return true;
        }
    }
}
