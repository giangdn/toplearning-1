<?php

namespace Modules\Quiz\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

/**
 * Modules\Quiz\Entities\QuizStatistic
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
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereT9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizStatistic whereYear($value)
 */
class QuizStatistic extends Model
{
    protected $table = 'el_quiz_statistic';
    protected $fillable = [
        't1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12','year'
    ];
    public $timestamps= false;
    public static function update_statistic_delete($quiz_date)
    {
        $year = (int) date('Y',strtotime($quiz_date));
        $month = (int) date('m',strtotime($quiz_date));
        $from = date('Y-m-01 00:00:01', strtotime($quiz_date));
        $to = date('Y-m-t 23:59:00', strtotime($quiz_date));
        $count_quiz = Quiz::whereBetween('created_at',[$from,$to])->where('quiz_type',3)->count();
        self::where('year','=',$year)->update(["t$month"=>$count_quiz]);
    }
    public static function update_statistic($quiz_id)
    {
        $quiz = Quiz::find($quiz_id);
        $quiz_date = $quiz->created_at;
        $year = (int) date('Y',strtotime($quiz_date));
        $month = (int) date('m',strtotime($quiz_date));
        $from = date('Y-m-01 00:00:01', strtotime($quiz_date));
        $to = date('Y-m-t 23:59:00', strtotime($quiz_date));
        $count_quiz = Quiz::whereBetween('created_at',[$from,$to])->where('quiz_type',3)->count();
        self::updateOrCreate(
            [
                'year'=> $year
            ],
            ["t$month" => $count_quiz]
        );
    }
}
