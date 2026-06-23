<?php

namespace App\Providers;

use App\Events\EventAttendance;
use App\Events\EventSignup;
use App\Events\Ping;
use App\Listeners\AttendanceHandler;
use App\Listeners\PongListener;
use App\Listeners\SignUpHandler;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->configureRateLimiting();
        $this->registerEventListeners();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Register the application's event listeners.
     */
    protected function registerEventListeners(): void
    {
        Event::listen(EventSignup::class, SignUpHandler::class);
        Event::listen(EventAttendance::class, AttendanceHandler::class);
        Event::listen(Ping::class, PongListener::class);
    }
}
