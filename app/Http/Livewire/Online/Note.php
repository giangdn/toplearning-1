<?php

namespace App\Http\Livewire\Online;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Online\Entities\OnlineCourseNote;

class Note extends Component
{
    use WithPagination;

    public $course_id;
    public $note_content;
    public $note;

    public function mount($course_id) {
        $this->course_id = $course_id;
    }

    public function comment() {
        $this->validate([
            'note_content' => 'required|string|max:1000',
        ], [
            'note_content.required' => 'Nội dung không được để trống',
        ]);
        $note_content = $this->note_content;
        if (strpos($note_content, 'sex') !== false || strpos($note_content, 'xxx') !== false || strpos($note_content, 'địt') !== false){
            $this->addError('note_content', 'Nội dung có từ nhạy cảm');
            $this->note_content = '';
        }else{
            if ($this->note == null) {
                $model = new OnlineCourseNote();
                $model->course_id = $this->course_id;
                $model->user_id = getUserId();
                $model->user_type = getUserType();
                $model->note = $this->note_content;
                $model->save();
            }else{
                $comment = OnlineCourseNote::findOrFail($this->note);
                $comment->note = $this->note_content;
                $comment->save();
            }

            $this->note_content = '';
        }

    }

    public function deleteComment($id)
    {
        $comment = OnlineCourseNote::findOrFail($id)->delete();
    }

    public function editComment($id)
    {
        $comment = OnlineCourseNote::findOrFail($id);
        $this->note_content = $comment->ask;
        $this->note = $comment->id;
    }

    public function render()
    {
        $comments = OnlineCourseNote::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
            'c.name',
        ])
            ->from('el_online_course_note AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 1);
            })
            ->leftJoin('el_quiz_user_secondary AS c', function ($sub){
                $sub->on('c.id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 2);
            })
            ->where('a.course_id', '=', $this->course_id)
            ->where('a.user_id', '=', getUserId())
            ->where('a.user_type', '=', getUserType())
            ->orderBy('a.id', 'desc')
            ->paginate(5);
        return view('livewire.online.note', [
            'comments' => $comments
        ]);
    }
}
