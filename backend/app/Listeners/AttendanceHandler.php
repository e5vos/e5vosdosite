<?php

namespace App\Listeners;

use App\Events\EventAttendance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AttendanceHandler
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EventAttendance  $event
     * @return void
     */
    public function handle(EventAttendance $event)
    {
        //
    }
}
