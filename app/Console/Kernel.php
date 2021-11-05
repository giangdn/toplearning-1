<?php

namespace App\Console;

use Composer\Autoload\ClassMapGenerator;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Cron\Entities\Cron;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (!\Schema::hasTable('el_cron'))
        {
            return;
        }
        $crons = Cron::enable()->get();
        foreach ($crons as $index => $cron) {
            $schedule->command($cron->command)->cron($cron->expression)->withoutOverlapping()
                ->before(function () use ($cron){
                    Cron::where(['id'=>$cron->id])->update(['start_time'=>date('H:i:s'),'end_time'=>null]);
                })
                ->onSuccess(function () use ($cron){
                Cron::where(['id'=>$cron->id])->update(['last_run'=>date('Y-m-d H:i:s'),'end_time'=>date('H:i:s')]);
                })
                ->onFailure(function () use ($cron){
                    \Log::info('chay cron '.$cron->command.' bị fail '.date('d/m/Y H:i:s'));
                })
            ;
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
