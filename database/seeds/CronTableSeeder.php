<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;
use Modules\Cron\Entities\Cron;

class CronTableSeeder extends Seeder
{
    public function run()
    {
        $commands = Cron::getAllCommand();
        foreach ($commands as $index=>$item) {
            $exists = Cron::where(['code' => $item->code])->exists();
            if (!$exists) {
                $command = explode('{',$item->code);
                $cron = Cron::firstOrNew(['code' => $item->code]);
                $cron->command = trim($command[0]);
                $cron->description = $item->name;
                $split = explode(' ',$item->expression);
                $cron->minute = $split[0];
                $cron->hour = $split[1];
                $cron->day = $split[2];
                $cron->month = $split[3];
                $cron->day_of_week = $split[4];
                $cron->expression = $item->expression;
                $cron->enabled = 1;
                $cron->save();
            }
        }
    }
}
