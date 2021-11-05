<?php

namespace Modules\AppNotification\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ConsoleCommandProvider extends ServiceProvider
{
    public function boot() {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
//            $schedule->command('app:send-notification')->everyMinute()->withoutOverlapping();
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            \Modules\AppNotification\Console\SendNotification::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
