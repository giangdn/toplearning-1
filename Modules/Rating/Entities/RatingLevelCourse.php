<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingLevelCourse extends Model
{
    protected $table = 'el_rating_level_course';
    protected $fillable = [
        'course_rating_level_id',
        'course_rating_level_object_id',
        'level',
        'user_id',
        'user_type',
        'course_id',
        'course_type',
        'rating_user',
        'user_update',
        'send',
    ];
    protected $primaryKey = 'id';
}
