<?php

namespace Modules\NewsOutside\Entities;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
use App\Traits\ChangeLogs;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class NewsOutside extends BaseModel
{
    protected $table = 'el_news_outside';
    protected $fillable = [
        'title',
        'content',
        'description',
        'views',
        'status',
        'image',
        'date_setup_icon',
        'number_setup',
        'category_id',
        'created_by',
        'updated_by',
        'user_view',
        'hot',
        'hot_public',
        'view_time',
        'type',
        'like_new'
    ];

    public static function getAttributeName() {
        return [
            'category_id' => 'Danh mục tin tức chung',
            'title' => 'Tiêu đề',
            'content' => 'Nội dung',
            'description' => 'Mô tả',
            'category_id' => 'Danh mục',
            'status'=>'Trang thái',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor'),
            'type' => 'thể loại',
        ];
    }

    public static function updateItemViews($id){
        $model = NewsOutside::find($id);

        DB::table('el_news_outside')
            ->where('id',$id)
            ->update([
                'views' => $model->views + 1,
                'view_time' => date('Y-m-d H:i:s'),
            ]);
    }
}
