<?php

namespace Modules\Rating\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class RatingLevels extends BaseModel
{
    protected $table = 'el_rating_levels';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function courses()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingLevelsCourses','rating_levels_id');
    }
}
