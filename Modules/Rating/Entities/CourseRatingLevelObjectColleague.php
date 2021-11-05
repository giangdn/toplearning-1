<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class CourseRatingLevelObjectColleague extends Model
{
    protected $table = 'el_course_rating_level_object_colleague';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_rating_level_id',
        'user_id',
        'rating_user_id',
        'rating_template_id',
    ];
}
