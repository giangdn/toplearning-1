<?php

namespace Modules\DailyTraining\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingCategory extends BaseModel
{
    protected $table = 'el_daily_training_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'status_video',
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên danh mục',
            'status_video' => 'Trạng thái video',
        ];
    }
}
