<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ResourceDidNoExistException;

use App\Http\Controllers\{
    Controller
};
use App\Models\{
    Permission,
};
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     *  add an organiser to an event
     */
    public function addPermission(Request $request)
    {
        $requestData = json_decode(request()->permission);
        if (Permission::where([
            'user_id' => $requestData->user_id,
            'event_id' => $requestData->event_id,
            'code' => $requestData->code,
        ])->exists()) abort(409, 'Permission already exists');
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
    public function removePermission(Request $request)
    {
        $permission = Permission::find([
            $request->user_id,
            $request->event_id,
            $request->code,
        ]);
        if ($permission === null) throw new ResourceDidNoExistException();
        $permission->delete();
        return response()->noContent();
    }
}
