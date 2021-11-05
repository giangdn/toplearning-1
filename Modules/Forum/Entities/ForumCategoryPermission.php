<?php

namespace Modules\Forum\Entities;

use Illuminate\Database\Eloquent\Model;

class ForumCategoryPermission extends Model
{
    protected $table = 'el_forum_category_permission';
    protected $fillable = [
        'forum_cate_id',
        'unit_id',
        'user_id'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'forum_cate_id' => 'Danh mục',
            'unit_id' => 'Vùng đơn vị',
        ];
    }

    public static function checkObjectUnit ($forum_cate_id, $unit_id){
        $query = self::query();
        $query->where('unit_id', '=', $unit_id);
        $query->where('forum_cate_id', '=', $forum_cate_id);
        return $query->exists();
    }

    public static function checkObjectUser ($forum_cate_id, $user_id){
        $query = self::query();
        $query->where('user_id', '=', $user_id);
        $query->where('forum_cate_id', '=', $forum_cate_id);
        return $query->exists();
    }
}
