<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserViewCourse extends Model
{
    protected $table = 'el_user_view_course';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'course_type',
        'course_id',
        'time_view',
    ];
}
