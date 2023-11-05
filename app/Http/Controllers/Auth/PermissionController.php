<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ResourceDidNoExistException;
use App\Helpers\PermissionType;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    Controller
};
use App\Models\{
    Permission,
    Event,
    User,
};
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    /**
     *  add an organiser to an event
     * @param int $eventId
     * @param int $userId
     */
    public function addPermission(int $userId)
    {
        $requestData = request()->permission;
        $permission = Permission::create([
            'user_id' => $requestData->user_id,
            'event_id' => $requestData->event_id,
            'code' => $requestData->code,
        ]);
        return response($permission->jsonSerialize(), 201);
    }

    /**
     *  remove a permission
     * @param int $eventId
     * @param int $userId
     */
    public function removePermission(int $userId)
    {
        $requestData = request()->permission;
        $permission = Permission::find([
            $requestData->user_id,
            $requestData->event_id,
            $requestData->code,
        ]);
        if ($permission === null) throw new ResourceDidNoExistException();
        $permission->delete();
        return response()->noContent();
    }
}
