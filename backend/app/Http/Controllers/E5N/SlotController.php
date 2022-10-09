<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use App\Models\Slot;
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
        $slot = new Slot();
        $slot->name = $request->name;
        $slot->type = $request->type;
        $slot->starts_at = $request->starts_at;
        $slot->ends_at = $request->ends_at;
        $slot->save();
        Cache::forget('e5n.slot.all');
        return response(Cache::rememberForever('e5n.slot.'.$slot->id, fn () => Slot::all()), 201);
    }

    /**
     * update a slot
     */
    public function update(Request $request, $slotId)
    {
        $slot = Slot::findOrFail($slotId);
        $slot->name = $request->name;
        $slot->type = $request->type;
        $slot->starts_at = $request->starts_at;
        $slot->ends_at = $request->ends_at;
        $slot->save();
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
}
