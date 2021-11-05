<?php

namespace Modules\Quiz\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class QuizAttempt
{
    public function handle(Request $request, Closure $next)
    {
        $quiz_id = $request->route('quiz_id');
        $part_id = $request->route('part_id');
        $attempt_id = $request->route('attempt_id');
    
        $quiz = Quiz::whereId($quiz_id)
            ->whereStatus(1)
            ->whereIsOpen(1)
            ->firstOrFail();
        
        $quiz->parts()
            ->from('el_quiz_part AS part')
            ->where('id', '=', $part_id)
            ->whereExists(function ($subquery) use ($quiz) {
                $subquery->select(['a.id'])
                    ->from('el_quiz_register AS a')
                    ->where('a.quiz_id', '=', $quiz->id)
                    ->where('a.user_id', '=', \Auth::id())
                    ->where('a.type', '=', \Auth::guard())
                    ->whereColumn('a.part_id', '=', 'part.id');
            })->firstOrFail();
        
        return $next($request);
    }
}
