<?php

namespace App\Http\Controllers\Auth;

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

class PermissionController extends Controller
{
    /**
     *  add an organiser to an event
     * @param int $eventId
     * @param int $userId
     */
    public function addPermission(int $userId, int $eventId = null, string $code = null)
    {
        $event = $eventId ? Event::findOrFail($eventId) : null;
        $user = User::findOrFail($userId);
        $permission = Permission::firstOrCreate([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'code' => $event ? PermissionType::Organiser->value : (in_array($code, array_column(PermissionType::cases(), 'value')) ? $code : abort(400, 'Invalid permission code.')),
        ]);
        return response()->json($permission, 201);
    }

    /**
     *  remove a permission
     * @param int $eventId
     * @param int $userId
     */
    public function removePermission(int $userId, int $eventId = null, string $code = null)
    {
        $permission = Permission::find([$userId, $eventId, $code]);
        if ($permission->first() === null) abort(400, 'User did not have permission.');
        $permission->delete();
        return response()->json($permission);
    }
}
