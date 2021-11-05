<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseActivityUrl
 *
 * @property int $id
 * @property string $url
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityUrl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityUrl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityUrl query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityUrl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityUrl whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityUrl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityUrl whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityUrl whereUrl($value)
 * @mixin \Eloquent
 * @property int $course_id
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityUrl whereCourseId($value)
 */
class OnlineCourseActivityUrl extends Model
{
    protected $table = 'el_online_course_activity_url';
    protected $primaryKey = 'id';
    protected $fillable = ['url', 'description'];
}
