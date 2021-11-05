<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ReportNew\Entities\BC17
 *
 * @property int $id
 * @property string|null $user_code
 * @property string|null $full_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $area
 * @property string|null $unit1_code
 * @property string|null $unit1_name
 * @property string|null $unit2_code
 * @property string|null $unit2_name
 * @property string|null $unit3_code
 * @property string|null $unit3_name
 * @property int|null $position_id
 * @property string|null $position_name
 * @property int|null $titles_id
 * @property string|null $titles_name
 * @property int|null $training_program_id
 * @property string|null $training_program_name
 * @property int|null $subject_id
 * @property string|null $subject_name
 * @property int|null $course_id
 * @property string|null $course_code
 * @property string|null $course_name
 * @property string|null $training_unit
 * @property string|null $training_type
 * @property string|null $training_address
 * @property string|null $course_time Thời lượng
 * @property string|null $start_date từ ngày
 * @property string|null $end_date đến ngày
 * @property string|null $time_schedule Thời gian
 * @property string|null $cost_held Chi phí tổ chức
 * @property string|null $cost_training Chi phí phòng đào tạo
 * @property string|null $cost_external Chi phí đào tạo bên ngoài
 * @property string|null $cost_teacher Chi phí giảng viên
 * @property string|null $cost_academy Chi phí học viện
 * @property string|null $cost_total Tổng chi phí
 * @property string|null $time_commit Thời gian cam kết
 * @property string|null $from_time_commit Thời gian cam kết từ ngày
 * @property string|null $to_time_commit Thời gian cam kết đến ngày
 * @property int|null $time_rest Thời gian còn lại
 * @property string|null $cost_refund Chi phí bồi hoàn
 * @property string|null $attend Tham gia
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 query()
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereAttend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCostAcademy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCostExternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCostHeld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCostRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCostTeacher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCostTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCostTraining($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCourseCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCourseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCourseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereFromTimeCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 wherePositionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTimeCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTimeRest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTimeSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTitlesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTitlesName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereToTimeCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTrainingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTrainingProgramName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTrainingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereTrainingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereUnit1Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereUnit1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereUnit2Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereUnit2Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereUnit3Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereUnit3Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereUserCode($value)
 * @mixin \Eloquent
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereUserId($value)
 * @property string|null $cost_student Chi phí học viên
 * @method static \Illuminate\Database\Eloquent\Builder|BC17 whereCostStudent($value)
 */
class BC17 extends Model
{
    protected $table = 'el_report_bc17';
    protected $fillable=[
        'id',
        'user_id',
        'user_code',
        'full_name',
        'email',
        'phone',
        'area_id',
        'area',
        'unit_id',
        'unit1_id',
        'unit1_code',
        'unit1_name',
        'unit2_id',
        'unit2_code',
        'unit2_name',
        'unit3_id',
        'unit3_code',
        'unit3_name',
        'position_id',
        'position_name',
        'titles_id',
        'titles_name',
        'training_program_id',
        'training_program_name',
        'subject_id',
        'subject_name',
        'course_id',
        'course_code',
        'course_name',
        'training_unit',
        'training_type_id',
        'training_type',
        'training_address',
        'course_time',
        'start_date',
        'end_date',
        'time_schedule',
        'cost_held',
        'cost_training',
        'cost_external',
        'cost_teacher',
        'cost_student',
        'cost_total',
        'time_commit',
        'from_time_commit',
        'to_time_commit',
        'time_rest',
        'cost_refund',
        'note',
    ];
    public static function sql($title_id,$unit_id,$area_id,$training_type_id, $from_date, $to_date)
    {
        BC17::addGlobalScope(new DraftScope('user_id'));
        $query = \DB::table('el_report_bc17')
            ->select('*');
        if ($from_date){
            $query->where('start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('end_date', '<=', date_convert($to_date));
        }
        if ($title_id){
            $query->where('titles_id', $title_id );
        }
        if ($unit_id){
            $query->where('unit_id', '=', $unit_id);
        }
        if ($area_id){
            $query->where('area_id', '=', $area_id);
        }
        if ($training_type_id){
            $query->where('training_type_id', '=', $training_type_id);
        }
        return $query;
    }
}
