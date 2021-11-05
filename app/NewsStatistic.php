<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\News\Entities\News;

/**
 * App\CourseStatistic
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
class NewsStatistic extends Model
{
    protected $table = 'el_news_statistic';
    protected $fillable = [
        'type','t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12','year',
    ];
    public $timestamps= false;

    public static function update_news_insert_statistic($type, $id)
    {
        $year = (int) date('Y');
        $month = "t".(int) date('m');
        $model = self::where("year",$year)->pluck($month)->toArray();
        $errors = array_filter($model);
        // dd($model);
        if (empty($errors)) {
            self::updateOrCreate(
                [
                    'year'=> $year,
                    'type'=> $type,
                ],
                [$month => 1]
            ); 
        } else {
            $model = self::where("year",$year)->first();
            self::updateOrCreate(
                [
                    'year'=> $year,
                    'type'=> $type,
                ],
                [$month => $model->$month + 1]
            );       
        }
        // $model = self::where("year",$year)->where('month',$month)->first();
        // if ($model == null) {
            // $model = new NewsStatistic();
            // $model->year = $year;
            // $model->month = $month;
            // $model->new_id = $id;
            // $model->{"t$month"} = 1;
            // $model->save();
        // } else {
            // $model->{"t$month"} = $model->{"t$month"} + 1;
            // $model->save();
        // }      
    }
}
