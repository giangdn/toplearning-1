<?php

namespace Modules\ReportNew\Console;

use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Console\Command;
use Modules\ReportNew\Entities\BC24;
use function GuzzleHttp\Psr7\str;

class ReportBC24 extends Command
{
    protected $signature = 'report:bc24';

    protected $description = 'BC24. Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo đơn vị (1 ngày chạy 1 lần 22h)';
    protected $expression ="0 22 * * *";
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

            /*if ($i> (int)date('m'))
                break;*/

            $prefix = \DB::getTablePrefix();
            $data =\DB::table('el_online_register_view as a')
                ->select(
                    'a.unit_id',
                    'a.unit_code',
                    'a.unit_name',
                    \DB::raw('count('.$prefix.'a.course_id) as class'),
                    \DB::raw('count('.$prefix.'a.status) as attend'),
                    \DB::raw('count('.$prefix.'c.id) as completed')
                )
                ->join('el_online_course as b','a.course_id','=','b.id')
                ->leftJoin('el_online_course_complete as c', function ($query){
                   $query->on('c.course_id','=','a.course_id');
                   $query->on('c.user_id','=','a.user_id');
                })
                ->where([
                    'b.status' => 1,
                    'a.status' => 1,
                    'a.user_type' => 1,
                ])
                ->where(\DB::raw('month('.$prefix.'b.start_date)'), '<=', $ii)
                ->where(function ($sub) use ($prefix, $ii){
                    $sub->orWhereNull('b.end_date');
                    $sub->orWhere(\DB::raw('month('.$prefix.'b.end_date)'), '>=', $ii);
                })
                ->whereNotNull('a.unit_code')
                ->groupBy(['a.unit_id','a.unit_code','a.unit_name'])
                ->get();

            foreach ($data as $index => $item) {
                BC24::updateOrCreate(
                    [
                        'code'=>$item->unit_code,
                        'year'=>date('Y')
                    ],
                    [
                        'unit_name'=>$item->unit_name,
                        "class_$i"=> $item->class,
                        "attend_$i"=> $item->attend,
                        "completed_$i"=> $item->completed,
                        "uncompleted_$i"=> $item->class>=$item->completed ? $item->class - $item->completed: 0,
                        'unit_by' => $item->unit_id,
                    ]);
            }
        }
        $this->info('Success');
    }
}
