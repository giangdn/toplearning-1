<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineRatingLevelObjectColleague extends Model
{
    protected $table = 'el_offline_rating_level_object_colleague';
    protected $primaryKey = 'id';
    protected $fillable = [
        'offline_rating_level_id',
        'user_id',
        'rating_user_id',
    ];
}
