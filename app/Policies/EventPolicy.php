<?php

namespace App\Policies;

use App\Exceptions\AttendanceRegisterDisabledException;
use App\Exceptions\SignupClosedException;
use App\Exceptions\SignupDisabledException;
use App\Exceptions\SignupRequiredException;
use App\Exceptions\WrongSignupTypeException;
use App\Helpers\PermissionType;
use App\Helpers\SlotType;
use App\Models\Event;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all permissions to admin users.
     *
     * @return bool
     */
    public function before(User $user)
    {
        return $user->hasPermission(PermissionType::Operator->value) ? true : null;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
     */
    public function update(User $user, ?Event $event = null)
    {
        $eventId = $event->id ?? request()->eventId;

        return $user->hasPermission(PermissionType::Admin->value) || $user->organisesEvent($eventId);
    }

    /**
     * Determine whether the user can set scores/ranks for the event.
     *
     * @return Response|bool
     */
    public function setScore(User $user, ?Event $event = null)
    {
        $eventId = $event->id ?? request()->eventId;

        return $user->hasPermission(PermissionType::Admin->value) || $user->organisesEvent($eventId);
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
     * @return Response|bool
     */
    public function delete(User $user, ?Event $event = null)
    {
        $eventId = $event->id ?? request()->eventId;

        return $user->hasPermission(PermissionType::Admin->value) || $user->organisesEvent($eventId);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function restore($user)
    {
        return $user->hasPermission(PermissionType::Admin->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return Response|bool
     */
    public function forceDelete()
    {
        return false;
    }

    /**
     * Determine if the user can attend the event.
     *
     *
     * @return bool
     *
     * @throws SignupDisabledException
     * @throws SignupClosedException
     * @throws WrongSignupTypeException
     */
    public function signup(User $user, ?Event $event = null)
    {
        if (! Setting::find('e5n.events.signup')?->value) {
            throw new SignupDisabledException;
        }
        $event ??= Event::findOrFail(request()->eventId);
        $attenderCode = request()->attender ?? $user->e5code ?? null;
        $attenderType = strlen($attenderCode) === 13 ? 'user' : 'team';
        if (! $event->isSignupOpen()) {
            throw new SignupClosedException;
        }
        if ($event->signup_type !== 'team_user' && $event->signup_type !== $attenderType) {
            throw new WrongSignupTypeException;
        }
        if ($attenderType === 'user') {
            $isAttender = is_numeric($attenderCode) ? $user->id == $attenderCode : $user->e5code === $attenderCode; // check for both id and e5code
        } else { // if team
            $isAttender = $user->isLeaderOfTeam($attenderCode);
        }

        return $isAttender || $user->hasPermission(PermissionType::Admin->value) || $user->hasPermission(PermissionType::TeacherAdmin->value);
    }

    /**
     * Determine if the user can cancel the signup for the event.
     *
     *
     * @return bool
     *
     * @throws SignupDisabledException
     * @throws SignupClosedException
     */
    public function unsignup(User $user, ?Event $event = null)
    {
        if (! request()->has('attender')) {
            return false;
        }

        if (! Setting::find('e5n.events.signup')?->value) {
            throw new SignupDisabledException;
        }

        $event = $event ?? Event::findOrFail(request()->eventId);
        if (! $event->isSignupOpen()) {
            throw new SignupClosedException;
        }

        return request()->attender === $user->e5code || $user->isLeaderOfTeam(request()->attender) || $user->hasPermission(PermissionType::Admin->value);
    }

    /**
     * Determine if the user can attend the event.
     *
     *
     * @return bool
     *
     * @throws AttendanceRegisterDisabledException
     * @throws SignupClosedException
     * @throws SignupRequiredException
     * @throws WrongSignupTypeException
     */
    public function attend(User $user, ?Event $event = null)
    {
        if (! Setting::find('e5n')?->value) {
            throw new AttendanceRegisterDisabledException;
        }
        $event ??= Event::findOrFail(request()->eventId)->load('slot');
        $attender = request()->attender ?? request()->user()->e5code;
        if ($event->signup_deadline == null && $event->signuppers()->filter(fn (mixed $signupper) => $signupper->getKey() == $attender || $signupper->e5code === $attender)->count() === 0) {
            throw new SignupRequiredException;
        }
        $attenderType = is_numeric($attender) || strlen($attender) === 13 ? 'user' : 'team';
        if ($event->signup_type !== 'team_user' && $event->signup_type !== $attenderType) {
            throw new WrongSignupTypeException;
        }
        if ($event->slot?->slot_type === SlotType::presentation->value) {
            return $user->hasPermission(PermissionType::Teacher->value) || $user->hasPermission(PermissionType::TeacherAdmin->value);
        } else { // if not a presentation
            if (! $event->isRunning()) {
                throw new SignupClosedException;
            }

            return $user->hasPermission(PermissionType::Admin->value) || $user->organisesEvent($event->id) || $user->scansEvent($event->id);
        }
    }
}
