<?php

namespace Modules\NewsOutside\Entities;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
use App\Traits\ChangeLogs;
use GuzzleHttp\Client;
use Spatie\Permission\Traits\HasRoles;

class NewsOutsideCategory extends BaseModel
{
    protected $table = 'el_news_outside_category';
    protected $fillable = [
        'icon',
        'name',
        'parent_id',
        'status',
        'sort',
        'stt_sort',
        'stt_sort_parent',
        'created_by',
        'updated_by'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'icon' => 'Icon',
            'name' => 'Tên danh mục',
            'parent_id'=>'Danh mục cha',
            'status'=>'Hiện trên trang chủ',
            'sort'=>'Sắp xếp',
            'stt_sort'=>'Số thứ tự sắp xếp',
            'stt_sort_parent'=>'Số thứ tự sắp xếp cấp cha',
            'created_by'=>trans('lageneral.creator'),
            'updated_by'=>trans('lageneral.editor')
        ];
    }

    public function child(){
        return $this->hasMany(NewsOutsideCategory::class, 'parent_id', 'id');
    }
}
