<?php

namespace Modules\Forum\Entities;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $table = 'el_forum';
    protected $fillable = [
        'icon',
        'name',
        'status',
        'category_id',
        'created_by',
        'updated_by'
    ];
    protected $primaryKey = 'id';

    public static function getAllForumCategory($category_id = 0){
        $query = self::query();
        $query->where('category_id', '=', $category_id);
        $query->where('status', '=', 1);
        return $query->get();
    }

    public static function getAttributeName() {
        return [
            'icon' => 'Icon',
            'name' => 'Tên chuyên mục',
            'status' => 'Trạng thái',
            'category_id' => 'Danh mục',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor')
        ];
    }

    public function category(){
        return $this->belongsTo('Modules\Forum\Entities\ForumCategory');
    }

    public function thread(){
        return $this->hasMany('Modules\Forum\Entities\ForumThread')->where('status',1);
    }

    public function getTotalViews(){
        $views = self::query()
            ->selectRaw('sum(views) as views')
            ->join('el_forum_thread', 'forum_id','el_forum.id')
            ->where('el_forum.id','=',$this->id)
            ->where('el_forum_thread.status', 1)
            ->first()->views;
        return $views;
    }

    public function getTotalComment(){
        $comments = ForumComment::query()
            ->leftJoin('el_forum_thread', 'thread_id','el_forum_thread.id')
            ->where('forum_id','=',$this->id)
            ->where('status', 1)
            ->count();
        return $comments;
    }
}
