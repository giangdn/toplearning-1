<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingCourse extends Model
{
    protected $table = 'el_rating_course';
    protected $fillable = [
        'template_id',
        'user_id',
        'user_type',
        'course_id',
        'type',
        'send',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'template_id' => 'Mẫu đánh giá',
            'user_id' => 'Người đánh giá',
            'course_id' => 'Khóa học',
        ];
    }

    public static function checkExists($course_id, $user_id, $type) {
        $user_type = getUserType();
        $query = RatingCourse::query();
        $query->where('course_id', '=', $course_id)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', $user_type)
            ->where('type', '=', $type);
        if (!$query->exists()) {
            return false;
        }

        $rating = $query->first();
        if ($rating->send == 1) {
            return true;
        }

        return false;
    }
}
