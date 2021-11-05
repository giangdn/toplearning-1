<?php

namespace App\Http\Livewire\Offline;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Offline\Entities\OfflineComment;

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
                $model = new OfflineComment();
                $model->course_id = $this->course_id;
                $model->user_id = \Auth::id();
                $model->content = $this->content;
                $model->save();
            }else{
                $comment = OfflineComment::findOrFail($this->comment_id);
                $comment->content = $this->content;
                $comment->save();
            }

            $this->content = '';
        }
    }

    public function deleteComment($id)
    {
        $comment = OfflineComment::findOrFail($id)->delete();
    }

    public function editComment($id)
    {
        $comment = OfflineComment::findOrFail($id);
        $this->content = $comment->content;
        $this->comment_id = $comment->id;
    }

    public function render()
    {
        $comments = OfflineComment::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar'
        ])
            ->from('el_offline_comment AS a')
            ->join('el_profile AS b', 'b.user_id', '=', 'a.user_id')
            ->where('a.course_id', '=', $this->course_id)
            ->orderBy('a.id', 'desc')
            ->paginate(5);

        return view('livewire.offline.comment', [
            'comments' => $comments
        ]);
    }
}
