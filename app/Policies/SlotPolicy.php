<?php

namespace App\Policies;

use App\Helpers\PermissionType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SlotPolicy
{
    use HandlesAuthorization;

    /**
     * Grand access to admins
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
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create()
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update()
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete()
    {
        return false;
    }

    /**
     * Determine whether the user can see free students.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function freeStudents(User $user)
    {
        return $user->hasPermission(PermissionType::Teacher->value) || $user->hasPermission(PermissionType::TeacherAdmin->value);
    }
}
