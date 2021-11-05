<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuizAttempts;

class BC16 extends Model
{
    public static function sql($quiz_id, $from_date, $to_date)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $query = QuizAttempts::query();
        $query->select([
            'a.*',
            'b.user_id',
            'b.code',
            'b.lastname',
            'b.firstname',
            'b.email',
            'c.name as title_name',
            'd.name as unit_name'
        ])
            ->from('el_quiz_attempts AS a')
            ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id')
            ->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code')
            ->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code')
            ->leftJoin('el_quiz_part AS e', 'e.id', '=', 'a.part_id')
            ->where('e.start_date','>=', $from_date)
            ->where('e.start_date','<=', $to_date)
            ->where('a.quiz_id', '=', $quiz_id);

        return $query;
    }

}
