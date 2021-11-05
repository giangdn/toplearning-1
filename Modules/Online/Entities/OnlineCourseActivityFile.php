<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseActivityFile
 *
 * @property int $id
 * @property string $path
 * @property string $extension
 * @property string|null $description
 * @property \Modules\Online\Entities\OnlineCourseActivity|null $course_activity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Warehouse|null $warehouse
 * @property int $course_id
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityFile whereCourseId($value)
 */
class OnlineCourseActivityFile extends Model
{
    protected $table = 'el_online_course_activity_file';
    protected $primaryKey = 'id';
    protected $fillable = [
        'extension',
        'path',
        'description',
    ];
    
    public function warehouse() {
        return $this->hasOne('App\Warehouse', 'file_path', 'path');
    }
    
    public function course_activity() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourseActivity', 'subject_id', 'id');
    }
    
    /**
     * Check complete.
     * @param int $user_id
     * @return bool
     * */
    public function checkComplete($user_id) {
        if (OnlineCourseActivityHistory::where('course_id', '=', $this->course_id)
            ->where('course_activity_id', '=', $this->course_activity->id)
            ->where('user_id', '=', $user_id)
            ->exists()) {
            return true;
        }
    
        return false;
    }
}
