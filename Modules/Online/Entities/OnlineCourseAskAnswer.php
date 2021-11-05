<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseAskAnswer
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property string $ask
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineComment whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineComment whereUserId($value)
 * @mixin \Eloquent
 */
class OnlineCourseAskAnswer extends Model
{
    protected $table = 'el_online_course_ask_answer';
    protected $fillable = [
        'course_id',
        'user_id_ask',
        'user_type_ask',
        'ask',
        'user_id_answer',
        'user_type_answer',
        'answer',
    ];
    protected $primaryKey = 'id';
}
