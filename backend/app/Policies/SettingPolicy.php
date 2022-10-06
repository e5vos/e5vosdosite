<?php

namespace App\Policies;

use App\Models\Setting;
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
        dd($user->permissions->pluck('code'));
        return $user->hasPermission('OPT') ? Response::allow() : Response::denyAsNotFound();
    }
}
