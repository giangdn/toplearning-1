<?php

namespace App\Http\Livewire\Situation;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\TopicSituations\Entities\CommentSituation;
use Modules\TopicSituations\Entities\ReplyCommentSituation;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;

class Comment extends Component
{
    use WithPagination;

    public $situation_id;
    public $topic_id;
    public $comment;
    public $comment_id;
    public $reply_comment;
    public $reply_comment_id;

    public function mount($situation_id, $topic_id) {
        $this->situation_id = $situation_id;
        $this->topic_id = $topic_id;
    }

    public function comment() {
        $this->validate([
            'comment' => 'required|string|max:1000',
        ], [
            'comment.required' => 'Nội dung không được để trống',
        ]);
        $comment = $this->comment;
        if (strpos($comment, 'sex') !== false || strpos($comment, 'xxx') !== false || strpos($comment, 'địt') !== false){
            $this->addError('comment', 'Nội dung có từ nhạy cảm');
            $this->comment = '';
        }else{
            if ($this->comment_id == null) {
                $model = new CommentSituation();
                $model->situation_id = $this->situation_id;
                $model->topic_id = $this->topic_id;
                $model->user_id = \Auth::id();
                $model->comment = $this->comment;
                $model->save();
            }else{
                $comment = CommentSituation::findOrFail($this->comment_id);
                $comment->comment = $this->comment;
                $comment->save();
            }
            $this->comment = '';
        }
    }

    public function reply($data) {
        $this->validate([
            'reply_comment' => 'required|string|max:1000',
        ], [
            'reply_comment.required' => 'Nội dung bình luận không được để trống',
        ]);
        $reply_comment = $this->reply_comment;
        if (strpos($reply_comment, 'sex') !== false || strpos($reply_comment, 'xxx') !== false || strpos($reply_comment, 'địt') !== false){
            $this->addError('reply_comment', 'Nội dung có từ nhạy cảm');
            $this->reply_comment = '';
        }else{
            if ($this->reply_comment_id == null) {
                $model = new ReplyCommentSituation();
                $model->comment_id = $data['get_comment_id'];
                $model->user_id = \Auth::id();
                $model->comment = $this->reply_comment;
                $model->save();
            }else{
                $reply_comment = ReplyCommentSituation::findOrFail($this->reply_comment_id);
                $reply_comment->comment = $this->reply_comment;
                $reply_comment->save();
            }

            $this->reply_comment = '';
        }

    }

    public function deleteComment($id)
    {
        $comment = CommentSituation::findOrFail($id)->delete();
    }

    public function editComment($id)
    {
        $comment = CommentSituation::findOrFail($id);
        $this->comment = $comment->comment;
        $this->comment_id = $comment->id;
    }

    public function render()
    {
        $comments = CommentSituation::select([
            'cs.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'p.avatar'
        ])
            ->from('el_comment_situation AS cs')
            ->join('el_profile AS p', 'p.user_id', '=', 'cs.user_id')
            ->where('cs.situation_id', '=', $this->situation_id)
            ->orderBy('cs.id', 'desc')
            // ->paginate(2);
            ->paginate(5, ['*'], 'get_reply_comments');
        return view('livewire.topic-situation.comment', [
            'comments' => $comments,
        ]);
    }
}
