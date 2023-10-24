<?php

namespace App\Listeners;

use App\Events\EventSignup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SignUpHandler
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
     * @param  \App\Events\EventSignup  $event
     * @return void
     */
    public function handle(EventSignup $event)
    {
        //
    }
}
