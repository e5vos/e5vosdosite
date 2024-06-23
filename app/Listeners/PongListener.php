<?php

namespace App\Listeners;

use App\Evens\Ping;

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
     * @return void
     */
    public function handle(Ping $event)
    {
        //
    }
}
