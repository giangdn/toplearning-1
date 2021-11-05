<?php

namespace Modules\ReportNew\Console;

use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Modules\Online\Entities\OnlineCourse;
use Modules\ReportNew\Entities\BC25;
use Modules\MergeSubject\Entities\MergeSubject;
use function GuzzleHttp\Psr7\str;

class ReportBC25 extends Command
{
    protected $signature = 'report:bc25';

    protected $description = 'BC25. Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo chuyên đề (1 ngày chạy 1 lần 23h)';
    protected $expression ="0 23 * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        for ($i=1;$i<=12;$i++){
            $ii = sprintf("%02d", $i);
            $start_date = date("Y-$ii-01 00:00:00");

            $d = new \DateTime($start_date);
            $end_date = $d->format('Y-m-t 23:59:59');

            if ($i> (int)date('m'))
                break;
            $prefix = \DB::getTablePrefix();
            $data =\DB::table('el_online_register_view as a')
                ->select('b.subject_id','b.subject_code','b.subject_name',\DB::raw('count('.$prefix.'a.status) as attend'), \DB::raw('count('.$prefix.'c.id) as completed'))
                ->join('el_online_course_view as b','a.course_id','=','b.id')
                ->leftJoin('el_online_course_complete as c', function ($query){
                   $query->on('c.course_id','=','a.course_id');
                   $query->on('c.user_id','=','a.user_id');
                })
                ->where(['b.status'=>1,'a.status'=>1])
                ->where('b.start_date','>=',$start_date)
                ->where('b.start_date','<=',$end_date)
                ->groupBy(['b.subject_id','b.subject_code','b.subject_name'])->get();

            foreach ($data as $index => $item) {
                $class = OnlineCourse::where('subject_id', $item->subject_id)
                    ->where('start_date','>=',$start_date)
                    ->where('start_date','<=',$end_date)
                    ->count();

                BC25::updateOrCreate(
                    ['subject_id'=>$item->subject_id, 'year'=>date('Y')],
                    [
                        'subject_code'=>$item->subject_code,
                        'subject_name'=>$item->subject_name,
                        "class_$i"=> $class,
                        "attend_$i"=> $item->attend,
                        "completed_$i"=> $item->completed,
                        "uncompleted_$i"=> $item->attend>=$item->completed ? $item->attend - $item->completed: 0
                    ]);
            }
        }
        $this->info('Success');
    }
}
