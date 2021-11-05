<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\Scorm
 *
 * @property int $id
 * @property string $origin_path
 * @property string $unzip_path
 * @property string $index_file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm query()
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm whereIndexFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm whereOriginPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm whereUnzipPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $error
 * @property int $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\ScormUser[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scorm whereStatus($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineCourseActivityScorm[] $course_activities
 * @property-read int|null $course_activities_count
 */
class Scorm extends Model
{
    protected $table = 'el_scorms';
    protected $fillable = [
        'origin_path',
        'unzip_path',
        'index_file',
        'status',
        'error',
    ];
    
    public function course_activities() {
        return $this->hasMany('Modules\Online\Entities\OnlineCourseActivityScorm', 'path', 'origin_path');
    }
}
