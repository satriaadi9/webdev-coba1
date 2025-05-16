<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        //
        Gate::define('insert-product', function(User $user){
            $allowedRoles = ['admin','owner'];
            return $user->roles->pluck('role')->intersect($allowedRoles)->isNotEmpty();
        });

        Gate::define('edit-product', function(User $user){
            $allowedRoles = ['admin','owner'];
            return $user->roles->pluck('role')->intersect($allowedRoles)->isNotEmpty();
        });

        Gate::define('delete-product', function(User $user){
            $allowedRoles = ['admin','owner'];
            return $user->roles->pluck('role')->intersect($allowedRoles)->isNotEmpty();
        });

        Gate::define('add-to-cart', function(User $user){
            $allowedRoles = ['customer'];
            return $user->roles->pluck('role')->intersect($allowedRoles)->isNotEmpty();
        });
    }
}
