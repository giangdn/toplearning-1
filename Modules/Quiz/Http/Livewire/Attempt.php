<?php

namespace Modules\Quiz\Http\Livewire;

use Livewire\Component;

class Attempt extends Component
{
    public $attempt;
    public $quiz;
    public $template_questions;
    public $categories;
    public $current_page = 1;
    public $limit;
    public $total_page;
    public $attempt_finish = false;
    
    public function nextPage() {
        $this->current_page ++;
    }
    
    public function backPage() {
        $this->current_page --;
    }
    
    public function submit() {
    
    }
    
    /**
     * Mount Quiz attempt
     * @param \Modules\Quiz\Entities\QuizAttempts $attempt
     * */
    public function mount($attempt) {
        $this->attempt = $attempt;
        $this->quiz = $attempt->quiz;
    
        $storage = \Storage::disk('local');
        $template = 'quiz/' . $this->attempt->quiz_id . '/attempt-' . $this->attempt->id .'.txt';
        $template = json_decode($storage->get($template), true);
    
        $this->template_questions = $template['questions'];
        
        $total = count( $this->template_questions );
        $this->limit = $this->attempt->quiz->questions_perpage;
    
        $this->total_page = ceil( $total/ $this->limit );
    }
    
    public function render()
    {
        $page = max($this->current_page, 1);
        $page = min($page, $this->total_page);
        $offset = ($page - 1) * $this->limit;
        if( $offset < 0 ) $offset = 0;
        
        return view('quiz::quiz.livewire.attempt', [
            'questions' => array_slice( $this->template_questions, $offset, $this->limit ),
        ]);
    }
}
