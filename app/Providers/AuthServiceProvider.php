<?php

namespace App\Providers;

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

        //
        Gate::define('Set interface', function($user) {
            if ( $user->email == '591466539@qq.com' || $user->email == 'fuzzywy@163.com' || $user->email == 'leon.liu@ericsson.com' ) {
                return true;
            } else {
                return false;
            }
        });
    }
}
