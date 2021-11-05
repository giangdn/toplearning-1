<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineRatingLevelObject extends Model
{
    protected $table = 'el_offline_rating_level_object';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'offline_rating_level_id',
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
