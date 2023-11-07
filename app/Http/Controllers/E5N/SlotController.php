<?php

namespace App\Http\Controllers\E5N;

use App\Helpers\PermissionType;
use App\Http\Controllers\Controller;
use App\Models\{
    Slot,
    User,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\SlotResource;
use App\Http\Resources\UserResource;

class SlotController extends Controller
{
    /**
     * return all slots
     */
    public function index()
    {
        return Cache::rememberForever('e5n.slot.all', fn () => SlotResource::collection(Slot::all())->jsonSerialize());
    }

    /**
     * create a new slot
     */
    public function store(Request $request)
    {
        $slot = Slot::create($request->all());
        $slot = new SlotResource($slot);
        Cache::forget('e5n.slot.all');
        return response(Cache::rememberForever('e5n.slot.' . $slot->id, fn () => $slot->jsonSerialize()), 201);
    }

    /**
     * update a slot
     */
    public function update(Request $request, $slotId)
    {
        $slot = (Slot::find($slotId) ?? abort(404));
        foreach ($request->all() as $key => $value) {
            $slot->$key = $value;
        }
        $slot->save();
        $slot = new SlotResource($slot);
        Cache::forget('e5n.slot.all');
        Cache::forever('e5n.slot.' . $slot->id, $slot->jsonSerialize());
        return Cache::get('e5n.slot.' . $slot->id);
    }

    /**
     * delete a slot
     */
    public function destroy($slotId)
    {
        $slot = Slot::findOrFail($slotId);
        $slot->delete();
        Cache::forget('e5n.slot.all');
        Cache::forget('e5n.slot.' . $slot->id);
        return response()->noContent();
    }

    /**
     * return all students who are not busy in this slot
     */
    public function freeStudents($slotId)
    {
        return Cache::remember("freeStudents" . $slotId, 60, function () use ($slotId) {
            $occupiedIds = Slot::findOrFail($slotId)->signups()->groupBy('user_id')->whereNotNull('user_id')->pluck('user_id')->toArray();
            return UserResource::collection(User::whereNotIn('id', $occupiedIds)->get())->jsonSerialize(); // not optimal
        });
    }

    public function nonAttendingStudents($slotId)
    {
        return Cache::remember("nonAttendingStudents" . $slotId, 60, function () use ($slotId) {
            return Slot::findOrFail($slotId)->signups()->join('users', 'users.id', '=', 'attendances.user_id')->where('attendances.is_present', false)->distinct()->get(['users.id', 'users.name', 'users.ejg_class', 'attendances.is_present'])->toArray();
        });
    }
    public function AttendingStudents($slotId)
    {
        return Cache::remember("attendingStudents" . $slotId, 60, function () use ($slotId) {
            return Slot::findOrFail($slotId)->signups()->where('attendances.is_present', true)->join('users', 'users.id', '=', 'attendances.user_id')->distinct()->get(['users.id', 'users.name', 'users.ejg_class'])->toArray()->jsonSerialize();
        });
    }
}
