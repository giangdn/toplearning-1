<?php

namespace Modules\Quiz\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;

class FrontendController extends Controller
{
    public function index()
    {
        $count_quiz = Quiz::countQuizUser();
        $user_id = Quiz::getUserId();
        $user_type = Quiz::getUserType();

        $query = QuizPart::query();
        $query->select([
            'a.id',
            'a.quiz_type',
            'a.name AS quiz_name',
            'a.limit_time',
            'a.pass_score',
            'a.max_score',
            'a.img',
            'a.view_result',
            'b.id AS part_id',
            'b.start_date AS start_date',
            'b.end_date AS end_date',
        ]);

        $query->from('el_quiz AS a')
            ->join('el_quiz_part AS b', function ($subquery) use ($user_id, $user_type) {
                $subquery->on('b.quiz_id', '=', 'a.id')
                    ->whereIn('b.id', function ($subquery2) use ($user_id, $user_type) {
                        $subquery2->select(['part_id'])
                            ->from('el_quiz_register')
                            ->where('user_id', '=', $user_id)
                            ->where('type', '=', $user_type)
                            ->whereColumn('quiz_id', '=', 'a.id');
                    });
            })
            ->where('a.status', '=', 1)
            ->whereExists(function ($subquery) use ($user_id, $user_type){
                $subquery->select(['id'])
                    ->from('el_quiz_register')
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', $user_type)
                    ->whereColumn('quiz_id', '=', 'a.id');
            });

        $quizs = $query->get();

        return view('themes.mobile.frontend.quiz.index', [
            'count_quiz' => $count_quiz,
            'quizs' => $quizs,
            'user_id' => $user_id,
            'user_type' => $user_type,
        ]);
    }
}
