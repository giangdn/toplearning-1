<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineResult;

/**
 * App\CourseResultStatistic
 *
 * @mixin \Eloquent
 * @property int $id
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
 * @property int $course_type
 * @property int $result
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereT9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseResultStatistic whereYear($value)
 */
class CourseResultStatistic extends Model
{
    protected $table = 'el_course_result_statistic';
    protected $fillable = [
        't1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12','year','course_type','result'
    ];
    public $timestamps= false;

    public static function update_statistic_delete($course_type,$course_date)
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
    public static function update_count_result_statistic($course_type,$result=0)
    {
        $year = (int) date('Y');
        $month = (int) date('m');
        $from = date('Y-m-01 00:00:01');
        $to = date('Y-m-t 23:59:00');
        if ($course_type==1)
            $count_current_month=OnlineResult::whereBetween('created_at',[$from,$to])->where('result','=',$result)->count();
        else
            $count_current_month=OfflineResult::whereBetween('created_at',[$from,$to])->where('result','=',$result)->count();
        self::updateOrCreate(
            [
                'year'=> $year,
                'course_type'=>$course_type,
                'result'=>$result
            ],
            ["t$month" => $count_current_month]
        );
    }
}
