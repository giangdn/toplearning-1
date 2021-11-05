<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineRegisterApprove
 *
 * @property int $id
 * @property int $register_id
 * @property int $course_id
 * @property int $user_id
 * @property int $approve_by
 * @property int $unit_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove whereApproveBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegisterApprove whereUserId($value)
 * @mixin \Eloquent
 */
class OfflineRegisterApprove extends Model
{
    protected $table = 'el_offline_register_approve';
    protected $primaryKey = 'id';
    protected $fillable = ['register_id'];

    public static function countApprove($course_id)
    {
        return OfflineRegisterApprove::query()->where('course_id','=',$course_id)->count();
    }
}
