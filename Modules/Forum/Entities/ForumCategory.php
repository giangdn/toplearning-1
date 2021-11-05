<?php

namespace Modules\Forum\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends BaseModel
{
    protected $table = 'el_forum_category';
    protected $fillable = [
        'icon',
        'name',
        'status',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'icon' => 'Icon',
            'name' => 'Tên danh mục',
            'status'=>'Trạng thái',
        ];
    }

    public function topic()
    {
        return $this->hasMany('Modules\Forum\Entities\Forum','category_id');
    }
}
