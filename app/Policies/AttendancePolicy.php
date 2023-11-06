<?php

namespace App\Policies;

use App\Helpers\PermissionType;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
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

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission(PermissionType::Teacher->value) || $user->hasPermission(PermissionType::TeacherAdmin->value);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Attendance $attendance)
    {
        return $user->organisesEvent($attendance->event_id) || $user->hasPermission(PermissionType::Admin->value) || $user->hasPermission(PermissionType::TeacherAdmin->value);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  int $event_id
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, $eventId)
    {
        return $user->organisesEvent($eventId) || $user->hasPermission(PermissionType::Admin->value);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update()
    {
        return false; // TODO
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Attendance $attendance)
    {
        return $user->organisesEvent($attendance->event_id) || $user->hasPermission(PermissionType::Admin->value);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore()
    {
        return false; // TODO
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete()
    {
        return false; // TODO
    }
}
