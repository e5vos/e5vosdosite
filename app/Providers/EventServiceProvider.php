<?php

namespace App\Providers;

use App\Events\EventAttendance;
use App\Events\EventSignup;
use App\Events\Ping;
use App\Listeners\AttendanceHandler;
use App\Listeners\PongListener;
use App\Listeners\SignUpHandler;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        EventSignup::class => [
            SignUpHandler::class,
        ],
        EventAttendance::class => [
            AttendanceHandler::class,
        ],
        Ping::class => [
            PongListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }
}
