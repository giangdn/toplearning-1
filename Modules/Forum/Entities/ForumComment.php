<?php

namespace Modules\Forum\Entities;

use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    protected $table = 'el_forum_comment';
    protected $fillable = [
        'comment',
        'thread_id',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $primaryKey = 'id';
    public static function getAttributeName() {
        return [
            'comment' =>'Bình luận',
            'thread_id'=>'Danh mục con',
            'created_by'=>trans('lageneral.creator'),
            'created_at'=>'Ngày tạo',
            'updated_at'=>trans('lageneral.editor')
            ];
    }
    public static function CountComment($comment=0){
        $query = self::query();
        $query->where('thread_id', '=', $comment);
        $count = $query->count();
        return $count;
    }

    public function thread()
    {
        return $this->belongsTo('Modules\Forum\Entities\ForumThread','thread_id');
    }
}
