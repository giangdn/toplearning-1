<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

/**
 * App\CourseStatistic
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $course_type
 * @property int|null $t1
 * @property int|null $t2
 * @property int|null $t3
 * @property int|null $t4
 * @property int|null $t5
 * @property int|null $t6
 * @property int|null $t7
 * @property int|null $t8
 * @property int|null $t9
 * @property int|null $t10
 * @property int|null $t11
 * @property int|null $t12
 * @property int $year
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereT9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatistic whereYear($value)
 */
class CourseStatistic extends Model
{
    protected $table = 'el_course_statistic';
    protected $fillable = [
        't1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12','year','course_type'
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
