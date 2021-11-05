<?php

namespace App\Http\Livewire\Quiz;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Quiz\Entities\QuizTemplateQuestion;
use Modules\Quiz\Entities\QuizTemplateQuestionAnswer;

class Question extends Component
{
    use WithPagination;

    public $question;
    public $essay;

    public function mount($question)
    {
        $this->question = $question;
    }

    public function chooseOne($id,$questionId)
    {
        QuizTemplateQuestionAnswer::where('question_id', '=', $questionId)->update(['selected' => 0]);
        QuizTemplateQuestionAnswer::findOrFail($id)->update(['selected'=>1]);
    }

    public function chooseMulti($id)
    {
        $answer = QuizTemplateQuestionAnswer::findOrFail($id);
        $answer->selected = $answer->selected == 1 ? 0 : 1;
        $answer->save();
    }

    public function doEssay($id)
    {
        if ($this->essay){
            QuizTemplateQuestion::where('id', '=', $id)->update(['text_essay' => $this->essay]);
            $this->essay = "";
        }

    }

    public function render()
    {
        return view('livewire.quiz.question');
    }
}
