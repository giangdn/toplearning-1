<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingLevelsRegister extends Model
{
    protected $table = 'el_rating_levels_register';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rating_levels_id',
        'user_id',
        'unit_id',
        'unit_code'
    ];
}
