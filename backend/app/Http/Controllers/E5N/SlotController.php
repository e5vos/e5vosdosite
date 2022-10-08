<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    /**
     * return all slots
     */
    public function index()
    {
        return Slot::all();
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
        return $slot;
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
        return $slot;
    }

    /**
     * delete a slot
     */
    public function destroy($slotId)
    {
        $slot = Slot::findOrFail($slotId);
        $slot->delete();
        return $slot;
    }
}
