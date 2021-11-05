<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ReportNew\Entities\BC24
 *
 * @property int $id
 * @property string|null $code Mã khu vực
 * @property string|null $area khu vực
 * @property int|null $class Số lớp
 * @property int|null $attend Số lượt tham dự
 * @property int|null $completed Số lượt hoàn thành
 * @property int|null $uncompleted Số lượt không hoàn thành
 * @property int $month
 * @property int $year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 query()
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereUncompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereYear($value)
 * @mixin \Eloquent
 * @property string|null $unit_name đơn vị
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereUnitName($value)
 * @property int|null $class_1
 * @property int|null $attend_1
 * @property int|null $completed_1
 * @property int|null $class_2
 * @property int|null $attend_2
 * @property int|null $completed_2
 * @property int|null $class_3
 * @property int|null $attend_3
 * @property int|null $completed_3
 * @property int|null $class_4
 * @property int|null $attend_4
 * @property int|null $completed_4
 * @property int|null $class_5
 * @property int|null $attend_5
 * @property int|null $completed_5
 * @property int|null $class_6
 * @property int|null $attend_6
 * @property int|null $completed_6
 * @property int|null $class_7
 * @property int|null $attend_7
 * @property int|null $completed_7
 * @property int|null $class_8
 * @property int|null $attend_8
 * @property int|null $completed_8
 * @property int|null $class_9
 * @property int|null $attend_9
 * @property int|null $completed_9
 * @property int|null $class_10
 * @property int|null $attend_10
 * @property int|null $completed_10
 * @property int|null $class_11
 * @property int|null $attend_11
 * @property int|null $completed_11
 * @property int|null $class_12
 * @property int|null $attend_12
 * @property int|null $completed_12
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereAttend9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereClass9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC24 whereCompleted9($value)
 */
class BC24 extends Model
{
    protected $table='el_report_bc24';
    protected $fillable=[
        'code',
        'unit_name',
        'class_1',
        'attend_1',
        'completed_1',
        'class_2',
        'attend_2',
        'completed_2',
        'class_3',
        'attend_3',
        'completed_3',
        'class_4',
        'attend_4',
        'completed_4',
        'class_5',
        'attend_5',
        'completed_5',
        'class_6',
        'attend_6',
        'completed_6',
        'class_7',
        'attend_7',
        'completed_7',
        'class_8',
        'attend_8',
        'completed_8',
        'class_9',
        'attend_9',
        'completed_9',
        'class_10',
        'attend_10',
        'completed_10',
        'class_11',
        'attend_11',
        'completed_11',
        'class_12',
        'attend_12',
        'completed_12',
        'year',
        'unit_by',
    ];
    public static function sql($month,$year)
    {
        $select = ['id','code','unit_name'];
        for ($i=1;$i<=$month;$i++){
            $select[] = "class_$i";
            $select[] = "attend_$i";
            $select[] = "completed_$i";
        }

        BC24::addGlobalScope(new DraftScope());
        $query = \DB::table('el_report_bc24');
        $query->where(['year'=>$year])->select($select)->orderBy('unit_name');
        return $query;
    }

}
