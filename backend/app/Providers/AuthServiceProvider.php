<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\{
    EventPolicy,
    LocationPolicy,
    BonusPointPolicy,
    UserPolicy,
};
use App\Models\{
    Event,
    Location,
    BonusPoint,
    User,
};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        Location::class => LocationPolicy::class,
        BonusPoint::class => BonusPointPolicy::class,
        User::class => UserPolicy::class,
    ];
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
