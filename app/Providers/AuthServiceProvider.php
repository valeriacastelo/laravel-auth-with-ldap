<?php

namespace App\Providers;

use App\CustomAuth\CustomEloquentUserProvider;
use App\CustomAuth\CustomSessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('custom.eloquent', function ($app, array $config) {
            return new CustomEloquentUserProvider($app['hash'], $config['model']);
        });

        Auth::extend('custom.session', function ($app, $name, array $config) {
            return new CustomSessionGuard($name,
                Auth::createUserProvider($config['provider']),
                app()->make('session.store'), request());
        });
    }
}
