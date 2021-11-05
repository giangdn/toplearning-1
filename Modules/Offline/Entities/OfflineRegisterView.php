<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineRegisterView
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $code
 * @property string|null $full_name
 * @property string|null $email
 * @property int|null $titles_id
 * @property string|null $titles_name
 * @property int|null $position_id
 * @property string|null $position_name
 * @property int|null $unit_id
 * @property string|null $unit_name
 * @property int $course_id
 * @property int|null $status trạng thái đăng ký
 * @property string|null $note
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView wherePositionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereTitlesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereTitlesName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $score Điểm thi
 * @property int|null $result kết quả
 * @property string|null $finish_date Ngày hoàn thành
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereCode($value)
 * @property int|null $approved_by_1 approved level 1
 * @property string|null $approved_date_1 ngày approved level 1
 * @property int|null $status_level_1 trạng thái approved level 1 (2 chưa duyệt / 0 từ chối / 1 đã duyệt)
 * @property int|null $approved_by_2 approved level 2
 * @property string|null $approved_date_2 ngày approved level 2
 * @property int|null $status_level_2 trạng thái approved level 2 (2 chưa duyệt / 0 từ chối / 1 đã duyệt)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereApprovedBy1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereApprovedBy2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereApprovedDate1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereApprovedDate2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereStatusLevel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereStatusLevel2($value)
 * @property string|null $unit_code
 * @property int|null $parent_unit_id
 * @property string|null $parent_unit_code
 * @property string|null $parent_unit_name
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereParentUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereParentUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereParentUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereUnitCode($value)
 * @property int|null $title_id
 * @property string|null $title_code
 * @property string|null $title_name
 * @property string|null $position_code
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView wherePositionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereTitleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineRegisterView whereTitleName($value)
 */
class OfflineRegisterView extends Model
{
    protected $table ='el_offline_register_view';
    protected $fillable = [
        'id',
        'user_id',
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
