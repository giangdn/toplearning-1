<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineRegisterView
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
 * @property int|null $area_id
 * @property string|null $area_code
 * @property string|null $area_name
 * @property int $course_id
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
 * @property string|null $approved_step
 * @property int|null $user_type
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView query()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereApprovedBy1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereApprovedBy2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereApprovedDate1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereApprovedDate2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereApprovedStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereAreaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereCronComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereParentUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereParentUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereParentUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView wherePositionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView wherePositionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereStatusLevel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereStatusLevel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereTitleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereTitleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegisterView whereUserType($value)
 * @mixin \Eloquent
 */
class OnlineRegisterView extends Model
{
    protected $table ='el_online_register_view';
    protected $fillable = [
        'id',
        'user_id',
        'user_type',
        'code',
        'full_name',
        'email',
        'title_id',
        'title_code',
        'title_name',
        'position_id',
        'position_code',
        'position_name',
        'unit_id',
        'unit_code',
        'unit_name',
        'parent_unit_id',
        'parent_unit_code',
        'parent_unit_name',
        'area_id',
        'area_code',
        'area_name',
        'course_id',
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
        'approved_step',
    ];
}
