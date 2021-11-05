<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineRatingLevelObjectColleague extends Model
{
    protected $table = 'el_online_rating_level_object_colleague';
    protected $primaryKey = 'id';
    protected $fillable = [
        'online_rating_level_id',
        'user_id',
        'rating_user_id',
    ];
}
