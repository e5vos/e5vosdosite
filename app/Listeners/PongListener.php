<?php

namespace App\Listeners;

use App\Evens\Ping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PongListener
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
     * @param  \App\Evens\Ping  $event
     * @return void
     */
    public function handle(Ping $event)
    {
        //
    }
}
