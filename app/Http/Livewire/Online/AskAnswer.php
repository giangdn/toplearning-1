<?php

namespace App\Http\Livewire\Online;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Online\Entities\OnlineCourseAskAnswer;

class AskAnswer extends Component
{
    use WithPagination;

    public $course_id;
    public $ask_content;
    public $ask_id;

    public function mount($course_id) {
        $this->course_id = $course_id;
    }

    public function comment() {
        $this->validate([
            'ask_content' => 'required|string|max:1000',
        ], [
            'ask_content.required' => 'Nội dung không được để trống',
        ]);
        $ask_content = $this->ask_content;
        if (strpos($ask_content, 'sex') !== false || strpos($ask_content, 'xxx') !== false || strpos($ask_content, 'địt') !== false){
            $this->addError('ask_content', 'Nội dung có từ nhạy cảm');
            $this->ask_content = '';
        }else{
            if ($this->ask_id == null) {
                $model = new OnlineCourseAskAnswer();
                $model->course_id = $this->course_id;
                $model->user_id_ask = getUserId();
                $model->user_type_ask = getUserType();
                $model->ask = $this->ask_content;
                $model->save();
            }else{
                $comment = OnlineCourseAskAnswer::findOrFail($this->ask_id);
                $comment->ask = $this->ask_content;
                $comment->save();
            }

            $this->ask_content = '';
        }

    }

    public function deleteComment($id)
    {
        $comment = OnlineCourseAskAnswer::findOrFail($id)->delete();
    }

    public function editComment($id)
    {
        $comment = OnlineCourseAskAnswer::findOrFail($id);
        $this->ask_content = $comment->ask;
        $this->ask_id = $comment->id;
    }

    public function render()
    {
        $comments = OnlineCourseAskAnswer::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
            'c.name',
        ])
            ->from('el_online_course_ask_answer AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id_ask')
                    ->where('a.user_type_ask', '=', 1);
            })
            ->leftJoin('el_quiz_user_secondary AS c', function ($sub){
                $sub->on('c.id', '=', 'a.user_id_ask')
                    ->where('a.user_type_ask', '=', 2);
            })
            ->where('a.course_id', '=', $this->course_id)
            ->where('a.user_id_ask', '=', getUserId())
            ->where('a.user_type_ask', '=', getUserType())
            ->where('a.status', '=', 1)
            ->orderBy('a.id', 'desc')
            ->paginate(5);

        return view('livewire.online.ask_answer', [
            'comments' => $comments
        ]);
    }
}
