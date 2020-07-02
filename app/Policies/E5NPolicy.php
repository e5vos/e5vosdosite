<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class E5NPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function admin(User $user){
        return $user->isAdmin;
    }

    public function scanner(User $user){
        return $user->isAdmin || $user->currentEvent()->exists();
    }

    public function e5n(User $user){
        return true; // e5n toggleswitch
    }

    public function editTeam(User $user, Team $team){
        return $team->admin()->get() == $user || $user->isAdmin;
    }
}
