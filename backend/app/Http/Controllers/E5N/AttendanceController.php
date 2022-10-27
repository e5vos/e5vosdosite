<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * return all attendees
     */
    public function index(int $eventId)
    {
        return Event::findOrFail($eventId)->attendees;
    }
}
