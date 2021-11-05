<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class CourseRatingLevelObject extends Model
{
    protected $table = 'el_course_rating_level_object';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rating_levels_id',
        'course_rating_level_id',
        'rating_template_id',
        'object_type',
        'num_user',
        'user_id',
        'rating_user_id',
        'start_date',
        'end_date',
        'time_type',
        'num_date',
        'object_view_rating',
        'user_completed',
    ];
}
