<?php

namespace Modules\Forum\Entities;

use Illuminate\Database\Eloquent\Model;

class ForumThread extends Model
{
    protected $table = 'el_forum_thread';
    protected $fillable = [
        'title',
        'content',
        'forum_id',
        'main_article',
        'status',
        'views',
        'created_by',
        'updated_by',
        'hashtag',
    ];
    protected $primaryKey = 'id';

    public static function getThreatByCategory($forum_id = 0, $thread_id = null){
        $query = self::query();
        $query->where('forum_id', '=', $forum_id);
        $query->where('status', '=', 1);
        if ($thread_id){
            $query->where('id', '!=', $thread_id);
        }
        $query->orderBy('updated_at','DESC')->paginate(10);
        return $query;
    }

    public static function updateItemViews($id){
        $model = ForumThread::findOrFail($id);
        $model->views = $model->views + 1;
        $model->save();
    }

    public static function CountCategory($forum_id=0)
    {
        $query = self::query();
        $query->where('forum_id', '=', $forum_id);
        $query->where('status', '=', 1);
        $count = $query->count();
        return $count;
    }

    public static function getAttributeName() {
        return [
            'title' => 'Bài viết',
            'content' => 'Nôi dung',
            'forum_id' => 'ID Chuyên mục',
            'status' => 'Trạng thái',
            'views' => 'Lượt xem',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor')
        ];
    }

    public static function getLastestComment($threadId)
    {
        $comment = ForumComment::where('thread_id',$threadId)
            ->select('el_profile.avatar','el_forum_comment.*')
            ->join('el_profile','created_by','el_profile.user_id')
            ->orderBy('el_forum_comment.id', 'DESC')
        ;
        return $comment->first();
    }

    public function category()
    {
        return $this->belongsTo('Modules\Forum\Entities\Forum','forum_id');
    }

    public function comments()
    {
        return $this->hasMany('Modules\Forum\Entities\ForumComment','thread_id');
    }

    public static function getLasterThread(){
        $thread = ForumThread::query()
            ->where('status', '=', 1)
            ->orderBy('updated_at', 'DESC')
            ->first();

        return $thread;
    }
}
