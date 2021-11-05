<?php

namespace Modules\PlanApp\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class PlanAppTemplate extends BaseModel
{
    protected $table = 'el_plan_app_template';
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên mẫu đánh giá',
        ];
    }
}
