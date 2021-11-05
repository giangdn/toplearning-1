<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Subject;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ReportNew\Entities\BC25
 *
 * @property int $id
 * @property int|null $subject_id Id chuyên đề
 * @property string|null $subject_code Mã chuyên đề
 * @property string|null $subject_name Chuyên đề
 * @property int|null $class_1 Số lớp
 * @property int|null $attend_1 Số lượt tham dự
 * @property int|null $completed_1 Số lượt hoàn thành
 * @property int|null $class_2 Số lớp
 * @property int|null $attend_2 Số lượt tham dự
 * @property int|null $completed_2 Số lượt hoàn thành
 * @property int|null $class_3 Số lớp
 * @property int|null $attend_3 Số lượt tham dự
 * @property int|null $completed_3 Số lượt hoàn thành
 * @property int|null $class_4 Số lớp
 * @property int|null $attend_4 Số lượt tham dự
 * @property int|null $completed_4 Số lượt hoàn thành
 * @property int|null $class_5 Số lớp
 * @property int|null $attend_5 Số lượt tham dự
 * @property int|null $completed_5 Số lượt hoàn thành
 * @property int|null $class_6 Số lớp
 * @property int|null $attend_6 Số lượt tham dự
 * @property int|null $completed_6 Số lượt hoàn thành
 * @property int|null $class_7 Số lớp
 * @property int|null $attend_7 Số lượt tham dự
 * @property int|null $completed_7 Số lượt hoàn thành
 * @property int|null $class_8 Số lớp
 * @property int|null $attend_8 Số lượt tham dự
 * @property int|null $completed_8 Số lượt hoàn thành
 * @property int|null $class_9 Số lớp
 * @property int|null $attend_9 Số lượt tham dự
 * @property int|null $completed_9 Số lượt hoàn thành
 * @property int|null $class_10 Số lớp
 * @property int|null $attend_10 Số lượt tham dự
 * @property int|null $completed_10 Số lượt hoàn thành
 * @property int|null $class_11 Số lớp
 * @property int|null $attend_11 Số lượt tham dự
 * @property int|null $completed_11 Số lượt hoàn thành
 * @property int|null $class_12 Số lớp
 * @property int|null $attend_12 Số lượt tham dự
 * @property int|null $completed_12 Số lượt hoàn thành
 * @property int $year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 query()
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereAttend9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereClass9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCompleted9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereSubjectCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC25 whereYear($value)
 * @mixin \Eloquent
 */
class BC25 extends Model
{
    protected $table='el_report_bc25';
    protected $fillable=[
        'subject_id',
        'subject_code',
        'subject_name',
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
    ];
    public static function sql($month,$year, $subject_id)
    {
        Subject::addGlobalScope(new DraftScope());
        $subjects_arr = Subject::where('status',1)->pluck('id')->toArray();

        $select = ['id','subject_code','subject_name'];
        for ($i=1;$i<=$month;$i++){
            $select[] = "class_$i";
            $select[] = "attend_$i";
            $select[] = "completed_$i";
        }

        $query = \DB::table('el_report_bc25');
        $query->whereIn('subject_id', $subjects_arr);

        if ($subject_id){
            $query->whereIn('subject_id', explode(',', $subject_id));
        }
        $query->where(['year'=>$year])->select($select)->orderBy('subject_name');
        return $query;
    }

}
