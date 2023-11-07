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
        $slot->update($request->all());
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
            $occupiedIds = Slot::findOrFail($slotId)->signups()->whereNotNull('user_id')->pluck('user_id')->toArray();
            return UserResource::collection(User::whereNotIn('id', $occupiedIds)->get())->jsonSerialize(); // not optimal
        });
    }

    public function nonAttendingStudents($slotId)
    {
        return Cache::remember("notAttendingStudents" . $slotId, 60, function () use ($slotId) {
            $missingIds = Slot::findOrFail($slotId)->signups()->where('is_present', false)->whereNotNull('user_id')->pluck('user_id')->toArray();
            return UserResource::collection(User::whereIn('id', $missingIds)->get())->jsonSerialize();  // not optimal
        });
    }
    public function AttendingStudents($slotId)
    {
        return Cache::remember("attendingStudents" . $slotId, 60, function () use ($slotId) {
            $attendingIds = Slot::findOrFail($slotId)->signups()->where('is_present', true)->whereNotNull('user_id')->pluck('user_id')->toArray();
            return UserResource::collection(User::whereIn('id', $attendingIds)->get())->jsonSerialize();  // not optimal
        });
    }
}
