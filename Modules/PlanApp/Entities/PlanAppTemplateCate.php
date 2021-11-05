<?php

namespace Modules\PlanApp\Entities;

use Illuminate\Database\Eloquent\Model;

class PlanAppTemplateCate extends Model
{
    protected $table = 'el_plan_app_template_cate';
    protected $fillable = [
        'name',
        'plan_app_id',
        'sort',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Đề mục',
            'plan_app_id' => 'Mã mẫu đánh giá',
            'sort' => 'Thứ tự đề mục',
        ];
    }
}
