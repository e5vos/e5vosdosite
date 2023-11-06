<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ResourceDidNoExistException;

use App\Http\Controllers\{
    Controller
};
use App\Models\{
    Permission,
};

class PermissionController extends Controller
{
    /**
     *  add an organiser to an event
     */
    public function addPermission()
    {
        $requestData = json_decode(request()->permission);
        $permission = Permission::create([
            'user_id' => $requestData->user_id,
            'event_id' => $requestData->event_id,
            'code' => $requestData->code,
        ]);
        return response($permission->jsonSerialize(), 201);
    }

    /**
     *  remove a permission
     */
    public function removePermission()
    {
        $requestData = json_decode(request()->permission);
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
