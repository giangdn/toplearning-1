<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizQuestionCategory;

class BC03 extends Model
{
    public static function sql($from_date, $to_date, $quiz_template_id)
    {
        $dbprefix = \DB::getTablePrefix();
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        QuestionCategory::addGlobalScope(new DraftScope());
        $query = QuestionCategory::query();
        $query->select([
            'c.id',
            'c.name as quiz_name',
            'el_question_category.name as cate_ques_name',
            'el_question_category.id as cate_ques_id',
        ]);
        $query->from('el_question_category');
        $query->leftJoin('el_question as question', 'question.category_id', '=', 'el_question_category.id');
        $query->leftJoin('el_quiz_question as b', function ($sub){
            $sub->on('b.qcategory_id', '=', 'el_question_category.id');
            $sub->orOn('b.question_id', '=', 'question.id');
        });
        $query->leftJoin('el_quiz as c', 'c.id', '=', 'b.quiz_id');

        if ($from_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'c.id)'), '>=', $from_date);
        }

        if ($to_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'c.id)'), '<=', $to_date);
        }

        if ($quiz_template_id){
            $query->where('c.quiz_template_id', '=', $quiz_template_id);
        }
        $query->groupBy(['el_question_category.id', 'el_question_category.name', 'c.id', 'c.name']);

        return $query;
    }

}
