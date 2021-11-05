<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CourseBookmark
 *
 * @property int $id
 * @property int $course_id
 * @property int $type
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourseBookmark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourseBookmark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourseBookmark query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourseBookmark whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourseBookmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourseBookmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourseBookmark whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourseBookmark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourseBookmark whereUserId($value)
 * @mixin \Eloquent
 */
class CourseBookmark extends Model
{
    protected $table = 'el_course_bookmark';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'type',
        'user_id'
    ];

    public static function checkExist($course_id, $course_type){
        $check = self::query()
            ->where('course_id', '=', $course_id)
            ->where('type', '=', $course_type)
            ->where('user_id', '=', \Auth::id());

        return $check->exists();
    }
}
