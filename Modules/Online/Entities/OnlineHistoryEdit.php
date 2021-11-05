<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineHistoryEdit
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property string $tab_edit
 * @property string $ip_address
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit whereTabEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineHistoryEdit whereUserId($value)
 * @mixin \Eloquent
 */
class OnlineHistoryEdit extends Model
{
    protected $table = 'el_online_history_edit';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'user_id',
        'tab_edit',
        'ip_address',
        'type',
    ];
}
