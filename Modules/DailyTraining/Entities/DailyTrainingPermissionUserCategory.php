<?php

namespace Modules\DailyTraining\Entities;

use Illuminate\Database\Eloquent\Model;

class DailyTrainingPermissionUserCategory extends Model
{
    protected $table = 'el_daily_training_permission_user_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'category_id',
        'user_id',
    ];

    public static function getAttributeName() {
        return [
            'category_id' => 'Danh mục',
            'user_id' => 'Người được phân quyền',
        ];
    }
}
