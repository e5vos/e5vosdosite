<?php

namespace App\Policies;

use App\Models\{
    Event,
    User,
    Setting,
};
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(?User $user)
    {
        return true;//
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, Event $event)
    {
        return true;//
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;//
    }

    /**
     * Determine whether the user can update the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Event $event)
    {
        return $user->isAdmin()  || $user->permissions()->whereBelongsTo($event)->exists();//
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Event $event)
    {
        return $user->isAdmin() || $event->organisers()->get()->contains($user);//
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Event $event)
    {
        return $user->isAdmin();//
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Event $event)
    {
        return $user->isAdmin();//
    }

    public function viewAnyAttendance(User $user){
        return $user->can("TAN") || $user->isAdmin();
    }

    public function viewAttendance(User $user, Event $event){
        return $user->can("TAN") || $user->can('update',$event);
    }

    public function setAttendance(User $user, Event $event){
        return $user->can("TAN") || $user->can('update',$event);
    }

    public function rate(User $user, Event $event){
        return $user->isAdmin() || $user->events()->get()->contains($event);
    }

    public function signup(User $user, Event $event){
        if($event->is_presentation){
            return Setting::check('e5nPresentationSignup');
        }else{
            return $event->start->isPast() && $event->start->isFuture();
        }
    }


}
