<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('image-modify','FilePolicy@imageModify');
        Gate::define('image-upload','FilePolicy@imageUpload');


        Gate::define('edit-settings', function ($user) {
            return $user->isAdmin;
        });



        Gate::define('edit-permissions', function ($user) {
            return $user->isAdmin;
        });

        Gate::define('e5n-admin', 'E5NPolicy@admin');


        Gate::define('e5n-scanner', 'E5NPolicy@scanner');


        /** E5N System availability */
        Gate::define('e5n', 'E5NPolicy@e5n');

        /* DISABLES AUTH FOR TESTING */
        Gate::before(function(){
            return true;
        })


    }
}
