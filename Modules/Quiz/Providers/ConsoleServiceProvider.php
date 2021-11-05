<?php

namespace Modules\Quiz\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ConsoleServiceProvider extends ServiceProvider
{
    public function boot() {
//        $this->app->booted(function () {
//            $schedule = $this->app->make(Schedule::class);
//            $schedule->command('attempt:complete')->everyMinute()->withoutOverlapping();
//            $schedule->command('quiz:complete')->everyMinute()->withoutOverlapping();
//            $schedule->command('mail:quiz_final')->everyTenMinutes()->withoutOverlapping();
//        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            \Modules\Quiz\Console\AttemptComplete::class,
            \Modules\Quiz\Console\QuizComplete::class,
            \Modules\Quiz\Console\QuizFinal::class,
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
