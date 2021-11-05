<?php

namespace App\Http\Livewire\Quiz;

use Livewire\Component;
use Livewire\WithPagination;

class Attempt extends Component
{
    use WithPagination;

    public $question;
    public $essay;

    public function mount($question)
    {
        $this->question = $question;
        $this->essay = $question->essay_text;
    }

    public function chooseAnswer($id)
    {

    }

    public function doEssay($questionId)
    {
        dd($this->essay);
    }

    public function render()
    {
        return view('livewire.quiz.attempt');
    }
}
