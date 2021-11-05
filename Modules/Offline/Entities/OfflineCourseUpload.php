<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineCourseUpload
 *
 * @property int $id
 * @property int $course_id
 * @property int $upload
 * @property int $user_id
 * @property int $num_star
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseUpload whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseUpload whereUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseUpload whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineCourseUpload extends Model
{
    protected $table = 'el_offline_course_upload';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'upload',
    ];

    public static function getAttributeName() {
        return [
            'course_id' => 'Khóa học',
            'upload' => 'Quản lý file',
        ];
    }
}
