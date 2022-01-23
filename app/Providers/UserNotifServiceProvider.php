<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserNotifServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->singleton('usernotifications', 'App\Common\UserNotifications');

    }
}
