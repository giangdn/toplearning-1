<?php

namespace Modules\Quiz\Http\Livewire;

use Livewire\Component;

class Question extends Component
{
    public $question;
    
    public $selected;
    
    public function updated() {
        dd($this->selected);
    }
    /**
     * Mount Quiz attempt
     * @param array $question
     * */
    public function mount($question) {
        $this->question = $question;
    }
    
    public function render()
    {
        return view('quiz::quiz.livewire.question');
    }
}
