<?php

namespace Modules\Online\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class OnlineRatingLevel extends BaseModel
{
    protected $table = 'el_online_rating_level';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'level',
        'rating_template_id',
        'rating_name',
        'created_by',
        'updated_by',
        'unit_by',
        'object_rating',
    ];

    public static function getAttributeName(){
        return [
            'level' => 'Cấp độ đánh giá',
            'rating_template_id' => 'Mẫu đánh giá',
            'rating_name' => 'Tên đánh giá',
        ];
    }
}
