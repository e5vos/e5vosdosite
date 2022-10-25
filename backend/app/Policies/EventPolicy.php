<?php

namespace App\Policies;

use App\Helpers\SlotType;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all permissions to admin users.
     *
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->hasPermission('OPT')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Event $event = null)
    {
        $eventId = $event->id ?? request()->id;
        return $user->hasPermission('ADM') || $user->organisesEvent($eventId);
    }


    /**
     * Determine if the user can view the model.
     */
    public function view()
    {
        return true;
    }

    /**
     * Determine if the user can create the model.
     */
    public function create()
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Event $event = null)
    {
        $eventId = $event->id ?? request()->id;
        return $user->hasPermission('ADM') || $user->organisesEvent($eventId);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore($user)
    {
        return $user->hasPermission('ADM');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete()
    {
        return false;
    }

    /**
     * Determine if the user can attend the event.
     * @param User $user
     * @param Event $event
     *
     * @return bool
     */
    public function signup(User $user, Event $event = null)
    {
        $event = $event ?? Event::findOrFail(request()->id);
        $attender = json_decode(request()->attender) ?? $user->id;
        if (!$event->isSignupOpen() || !($event->signup_type === 'team_user' || $attender->type === $event->signup_type)) {
            return false;
        }
        $attender = $attender->type == 'user' ? $user->id == $attender->id : $user->teams->contains($attender->id);
        return $user->hasPermission('ADM') || $attender;
    }
    /**
     * Determine if the user can attend the event.
     * @param User $user
     * @param Event $event
     *
     * @return bool
     */
    public function attend(User $user, Event $event = null)
    {
        $event = $event ?? Event::findOrFail(request()->id);
        $attender = json_decode(request()->attender) ?? $user->id;
        if ($event->signup_type && !$event->signuppers()->find($attender->id)) {
            return false;
        }
        return $event->slot->slot_type == SlotType::presentation ? $user->hasPermission('TCH') : ($user->organisesEvent($event->id) || $user->hasPermission('ADM'));
    }
}
