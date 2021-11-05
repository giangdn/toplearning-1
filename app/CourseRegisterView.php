<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CourseRegisterView
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $code
 * @property string|null $full_name
 * @property string|null $email
 * @property int|null $title_id
 * @property string|null $title_code
 * @property string|null $title_name
 * @property int|null $position_id
 * @property string|null $position_code
 * @property string|null $position_name
 * @property int|null $unit_id
 * @property string|null $unit_code
 * @property string|null $unit_name
 * @property int|null $parent_unit_id
 * @property string|null $parent_unit_code
 * @property string|null $parent_unit_name
 * @property int $course_id
 * @property int $course_type
 * @property int|null $status trạng thái đăng ký
 * @property string|null $note
 * @property string|null $score Điểm thi
 * @property int|null $result kết quả
 * @property string|null $finish_date Ngày hoàn thành
 * @property int|null $approved_by_1 approved level 1
 * @property string|null $approved_date_1 ngày approved level 1
 * @property int|null $status_level_1 trạng thái approved level 1 (2 chưa duyệt / 0 từ chối / 1 đã duyệt)
 * @property int|null $approved_by_2 approved level 2
 * @property string|null $approved_date_2 ngày approved level 2
 * @property int|null $status_level_2 trạng thái approved level 2 (2 chưa duyệt / 0 từ chối / 1 đã duyệt)
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $cron_complete
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereApprovedBy1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereApprovedBy2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereApprovedDate1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereApprovedDate2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereCronComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereParentUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereParentUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereParentUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView wherePositionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView wherePositionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereStatusLevel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereStatusLevel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereTitleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereTitleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRegisterView whereUserId($value)
 * @mixin \Eloquent
 */
class CourseRegisterView extends Model
{
    protected $table='el_course_register_view';
    protected $guarded = ['id'];
    protected $hidden = ['id'];
    protected $fillable=[
        'register_id',
        'user_id',
        'user_type',
        'code',
        'full_name',
        'email',
        'titles_id',
        'titles_code',
        'titles_name',
        'position_id',
        'position_code',
        'position_name',
        'unit_id',
        'unit_code',
        'unit_name',
        'parent_unit_id',
        'parent_unit_code',
        'parent_unit_name',
        'course_id',
        'course_type',
        'status',
        'note',
        'created_by',
        'updated_by',
        'unit_by',
        'score',
        'result',
        'finish_date',
        'approved_by_1',
        'approved_date_1',
        'status_level_1',
        'approved_by_2',
        'approved_date_2',
        'status_level_2',
        'cron_complete',
    ];
}
