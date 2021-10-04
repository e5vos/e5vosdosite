<?php

namespace App\Policies;

use App\User;
use App\Team;
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

    /**
     * Admin Access Restrictions
     *
     * @param  User $user
     * @return bool Access Granted
     */
    public function admin(User $user){
        return $user->isAdmin();
    }

    /**
     * Code Scanner Restrictions
     *
     * @param  User $user
     * @return bool Access Granted
     */
    public function scanner(User $user){
        return $user->isAdmin || $user->currentEvent()->exists();
    }


    /**
     * E5N System Restrictions
     *
     * @return bool Access Granted
     */
    public function e5n(){
        return true; // e5n toggleswitch
    }

    public function presentationSignupEnabled(){
        return true;
       //return
    }

    public function editTeam(User $user, Team $team){
        return $team->admin()->get() == $user || $user->isAdmin();
    }

}
