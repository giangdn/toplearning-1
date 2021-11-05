<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingLevelsCourses extends Model
{
    protected $table = 'el_rating_levels_courses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rating_levels_id',
        'course_id',
        'course_type',
    ];
}
