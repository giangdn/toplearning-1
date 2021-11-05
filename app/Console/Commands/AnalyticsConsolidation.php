<?php

namespace App\Console\Commands;

use App\Analytics;
use App\AnalyticsMonth;
use Illuminate\Console\Command;

class AnalyticsConsolidation extends Command
{
    protected $signature = 'analytics:consolidation';

    protected $description = 'Thống kê theo tháng chạy 23h hàng ngày (0 23 * * *)';

    protected $expression ='0 23 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $analytics = Analytics::where('day', '=', date('Y-m-d'))
            ->get();

        foreach ($analytics as $analytic) {
            $month = date('Y-m', strtotime($analytic->created_at));

            $model = AnalyticsMonth::firstOrNew([
                'user_id' => $analytic->user_id,
                'month' => $month
            ]);

            $minute = round((strtotime($analytic->end_date) - strtotime($analytic->start_date)) / 60, 2);
            $minute = $model->minute ? $model->minute + $minute : $minute;
            $access = $model->access ? $model->access + 1 : 1;

            $model->user_id = $analytic->user_id;
            $model->month = $month;
            $model->access = $access;
            $model->minute = $minute;
            $model->save();
        }
    }
}
