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

class SlotController extends Controller
{
    /**
     * return all slots
     */
    public function index()
    {
        return Cache::rememberForever('e5n.slot.all', fn () => Slot::all());
    }

    /**
     * create a new slot
     */
    public function store(Request $request)
    {
        $slot = Slot::create($request->all());
        Cache::forget('e5n.slot.all');
        return response(Cache::rememberForever('e5n.slot.'.$slot->id, fn () => $slot), 201);
    }

    /**
     * update a slot
     */
    public function update(Request $request, $slotId)
    {
        $slot = (Slot::find($slotId) ?? abort(404));
        $slot->update($request->all());
        Cache::forget('e5n.slot.all');
        Cache::put('e5n.slot.'.$slot->id, $slot);
        return Cache::get('e5n.slot.'.$slot->id);
    }

    /**
     * delete a slot
     */
    public function destroy($slotId)
    {
        $slot = Slot::findOrFail($slotId);
        $slot->delete();
        Cache::forget('e5n.slot.all');
        return Cache::pull('e5n.slot.'.$slot->id);
    }

    /**
     * return all students who are not busy in this slot
     */
    public function freeStudents($slotId)
    {
        return User::whereRelation('permissions', 'code', PermissionType::Student->value)->whereDoesntHave('events', function ($query) use ($slotId) {
            $query->where('slot_id', $slotId);
        })->get();
    }
}
