<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

/**
 * App\VisitsStatistic
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string|null $value
 * @property string $type
 * @property string|null $year
 * @method static \Illuminate\Database\Eloquent\Builder|VisitsStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VisitsStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VisitsStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|VisitsStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisitsStatistic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisitsStatistic whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisitsStatistic whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VisitsStatistic whereYear($value)
 */
class VisitsStatistic extends Model
{
    protected $table = 'el_visits_statistic';
    protected $fillable = [
        'name','value','type','year'
    ];
    public $timestamps= false;

    public static function update_course_delete_statistic($course_type,$course_date)
    {
        $year = (int) date('Y',strtotime($course_date));
        $month = (int) date('m',strtotime($course_date));
        $from = date('Y-m-01 00:00:01', strtotime($course_date));
        $to = date('Y-m-t 23:59:00', strtotime($course_date));
        if ($course_type==1)
            $count_course = OnlineCourse::whereBetween('start_date',[$from,$to])->count();
        else
            $count_course = OfflineCourse::whereBetween('start_date',[$from,$to])->count();
        self::where('course_type','=',$course_type)->where('year','=',$year)->update(["t$month"=>$count_course]);
    }
    public static function update_course_insert_statistic($course_id,$course_type)
    {
        if ($course_type==1)
            $course = OnlineCourse::findOrFail($course_id);
        else
            $course = OfflineCourse::findOrFail($course_id);
        $course_date = $course->start_date;
        $year = (int) date('Y',strtotime($course_date));
        $month = (int) date('m',strtotime($course_date));
        $from = date('Y-m-01 00:00:01', strtotime($course->start_date));
        $to = date('Y-m-t 23:59:00', strtotime($course->start_date));
        if ($course_type==1)
            $count_course = OnlineCourse::whereBetween('start_date',[$from,$to])->count();
        else
            $count_course = OfflineCourse::whereBetween('start_date',[$from,$to])->count();
        self::updateOrCreate(
            [
                'year'=> $year,
                'course_type'=>$course_type
            ],
            ["t$month" => $count_course]
        );
    }
    public static function update_course_update_statistic($course_id,$course_type,$date_original)
    {
        if ($course_type==1)
            $course = OnlineCourse::findOrFail($course_id);
        else
            $course = OfflineCourse::findOrFail($course_id);

        $month_ori = date('Y-m-01',strtotime($date_original));
        $month_change = date('Y-m-01',strtotime($course->start_date));
        $arr[] =(object)['date'=> $course->start_date];
        if ($month_ori<>$month_change)
            $arr[]=(object)['date'=>$date_original];
        $list = collect($arr);
        foreach ($list as $item) {
            $year = (int)date('Y', strtotime($item->date));
            $month = (int)date('m', strtotime($item->date));
            $from = date('Y-m-01 00:00:01', strtotime($item->date));
            $to = date('Y-m-t 23:59:00', strtotime($item->date));
            if ($course_type == 1)
                $count_course = OnlineCourse::whereBetween('start_date', [$from, $to])->count();
            else
                $count_course = OfflineCourse::whereBetween('start_date', [$from, $to])->count();
            self::updateOrCreate(
                [
                    'year' => $year,
                    'course_type' => $course_type
                ],
                ["t$month" => $count_course]
            );
        }
    }
}
