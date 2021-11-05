<?php

namespace Modules\Report\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Report\Console\ExportReport;
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
        $this->app->booted(function () {
//            $schedule = $this->app->make(Schedule::class);
//            $schedule->command('report:export')->everyMinute()->withoutOverlapping();
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

            ExportReport::class,
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
