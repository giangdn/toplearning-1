<?php

namespace App\Http\Livewire\Online;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Online\Entities\OnlineComment;

class Comment extends Component
{
    use WithPagination;

    public $course_id;
    public $avg_star;
    public $content;
    public $comment_id;

    public function mount($course_id,$avg_star) {
        $this->course_id = $course_id;
        $this->avg_star = $avg_star;
    }

    public function comment() {
        $this->validate([
            'content' => 'required|string|max:1000',
        ], [
            'content.required' => 'Nội dung không được để trống',
        ]);
        $content = $this->content;
        if (strpos($content, 'sex') !== false || strpos($content, 'xxx') !== false || strpos($content, 'địt') !== false){
            $this->addError('content', 'Nội dung có từ nhạy cảm');
            $this->content = '';
        }else{
            if ($this->comment_id == null) {
                $model = new OnlineComment();
                $model->course_id = $this->course_id;
                $model->user_id = getUserId();
                $model->user_type = getUserType();
                $model->content = $this->content;
                $model->save();
            }else{
                $comment = OnlineComment::findOrFail($this->comment_id);
                $comment->content = $this->content;
                $comment->save();
            }

            $this->content = '';
        }

    }

    public function deleteComment($id)
    {
        $comment = OnlineComment::findOrFail($id)->delete();
    }

    public function editComment($id)
    {
        $comment = OnlineComment::findOrFail($id);
        $this->content = $comment->content;
        $this->comment_id = $comment->id;
    }

    public function render()
    {
        $comments = OnlineComment::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
            'c.name',
        ])
            ->from('el_online_comment AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 1);
            })
            ->leftJoin('el_quiz_user_secondary AS c', function ($sub){
                $sub->on('c.id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 2);
            })
            ->where('a.course_id', '=', $this->course_id)
            ->orderBy('a.id', 'desc')
            ->paginate(5);
        return view('livewire.online.comment', [
            'comments' => $comments
        ]);
    }
}
