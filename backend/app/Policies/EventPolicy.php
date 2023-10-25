<?php

namespace App\Policies;

use App\Exceptions\NoE5NException;
use App\Exceptions\SignupClosedException;
use App\Exceptions\SignupRequiredException;
use App\Exceptions\WrongSignupTypeException;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use App\Models\Event;
use App\Models\Setting;
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
        $eventId = $event->id ?? request()->eventId;
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
        $eventId = $event->id ?? request()->eventId;
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
        if (!Setting::find('e5n.events.signup')?->value) {
            throw new NoE5NException();
        }
        $event = $event ?? Event::findOrFail(request()->eventId);
        $attenderCode = request()->attender ?? $user->e5code ?? null;
        $attenderType = strlen($attenderCode) === 13 ? 'user' : 'team';
        if (!$event->isSignupOpen()) {
            throw new SignupClosedException();
        }
        if ($event->signup_type !== 'team_user' && $event->signup_type !== $attenderType) {
            throw new WrongSignupTypeException();
        }
        $attender = $attenderType == 'user' ? (is_numeric($attenderCode) ? $user->id == $attenderCode : $user->e5code === $attenderCode) : $user->isLeaderOfTeam($attenderCode);
        return $attender || $user->hasPermission(PermissionType::Aadmin->value) || $user->hasPermission(PermissionType::Teacher->value) || $user->hasPermission(PermissionType::TeacherAdmin->value);
    }

    /**
     * Determine if the user can cancel the signup for the event.
     * @param User $user
     * @param Event $event
     *
     * @return bool
     */
    public function unsignup(User $user, Event $event = null)
    {
        if (!Setting::find('e5n.events.signup')?->value) {
            throw new NoE5NException();
        }
        $event = $event ?? Event::findOrFail(request()->eventId);
        if (!$event->isSignupOpen()) {
            throw new SignupClosedException();
        }
        if (!request()->has('attender')) {
            abort(400, 'No attender specified');
        }
        return request()->attender === $user->e5code || $user->isLeaderOfTeam(request()->attender) || $user->hasPermission('ADM');
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
        if (!Setting::find('e5n')?->value) {
            throw new NoE5NException();
        }
        $event ??= Event::findOrFail(request()->eventId);
        $attender = request()->attender ?? request()->user()->e5code;
        if (isset($event->signup_type) && !$event->signuppers()->find(strlen($attender) === 13 ? 'e5code' : 'code', $attender)) {
            return new SignupRequiredException();   
        }
        return $event->slot->slot_type === SlotType::presentation->value ? $user->hasPermission('TCH') : ($user->organisesEvent($event->id) || $user->hasPermission('ADM'));
    }
}
