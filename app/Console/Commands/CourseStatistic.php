<?php

namespace App\Console\Commands;

use App\CourseView;
use App\Models\CourseStatisticGeneral;
use App\VisitsStatistic;
use Illuminate\Console\Command;

class CourseStatistic extends Command
{
    protected $signature = 'command:course_statistic_general';

    protected $description = 'thống kê khóa học chạy 5 phút/lần (*/5 * * * *)';
    protected $expression ='*/5 * * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $courseTotal = CourseView::count();
        $courseHeld = CourseView::where(['status'=>1])->where('start_date','<=',get_date(now(),'Y-m-d'))->count();
        $courseNotHeld = CourseView::where(['status'=>1])->where('start_date','>',get_date(now(),'Y-m-d'))->count();
        $coursePending = CourseView::whereStatus(2)->count();
        $courseDeny = CourseView::whereStatus(0)->count();
        CourseStatisticGeneral::whereRaw("1=1")->delete();
        CourseStatisticGeneral::insert([
            'course_held'=>$courseHeld,
            'course_not_held'=>$courseNotHeld,
            'course_pending'=>$coursePending,
            'course_deny'=>$courseDeny,
            'course_total'=>$courseTotal,
        ]);
    }

}
