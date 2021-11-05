<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineRegisterApprove
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
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove whereApproveBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegisterApprove whereUserId($value)
 * @mixin \Eloquent
 */
class OnlineRegisterApprove extends Model
{
    protected $table = 'el_online_register_approve';
    protected $primaryKey = 'id';
    protected $fillable = ['register_id','approved_step'];

    public static function countApprove($course_id)
    {
        return OnlineRegisterApprove::query()->where('course_id','=',$course_id)->count();
    }
}
