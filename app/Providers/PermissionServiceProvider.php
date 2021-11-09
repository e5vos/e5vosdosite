<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Models\Permission;
use Illuminate\Support\Facades\{
    Gate,
    Blade
};

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            Permission::get()->map(function ($permission) {
                Gate::define($permission->permission, function ($user) use ($permission) {
                    return $user->permissions()->where('permission',$permission)->exists();
                });
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
