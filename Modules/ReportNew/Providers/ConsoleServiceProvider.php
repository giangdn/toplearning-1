<?php

namespace Modules\ReportNew\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\ReportNew\Console\ExportReportNew;
use Modules\ReportNew\Console\ReportBC17;
use Modules\ReportNew\Console\ReportBC18;
use Modules\ReportNew\Console\ReportBC22;
use Modules\ReportNew\Console\ReportBC24;
use Modules\ReportNew\Console\ReportBC25;
use Modules\ReportNew\Console\ReportNewBC08Update;
use Modules\ReportNew\Console\ReportNewBC13Update;

class ConsoleServiceProvider extends ServiceProvider
{
    public function boot() {
//        $this->app->booted(function () {
//            $schedule = $this->app->make(Schedule::class);
//            $schedule->command('report:bc17')->dailyAt('01:00')->withoutOverlapping();
//            $schedule->command('report:bc18')->dailyAt('01:00')->withoutOverlapping();
//            $schedule->command('report:bc22')->dailyAt('01:00')->withoutOverlapping();
//            $schedule->command('report:bc24')->dailyAt('22:00')->withoutOverlapping();
//            $schedule->command('report:bc25')->dailyAt('22:00')->withoutOverlapping();
//            $schedule->command('report_new_bc08:update')->dailyAt('01:00')->withoutOverlapping();
//            $schedule->command('report_new_bc13:update')->dailyAt('01:00')->withoutOverlapping();
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

            ReportBC17::class,
            ReportBC18::class,
            ReportBC22::class,
            ReportBC24::class,
            ReportBC25::class,
            ReportNewBC08Update::class,
            ReportNewBC13Update::class,
            ExportReportNew::class,
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
