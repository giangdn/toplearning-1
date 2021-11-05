<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuizRegister;

class BC21 extends Model
{
    public static function sql($quiz_id, $from_date, $to_date)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $query = QuizRegister::query();
        $query->select([
            'c.id AS title_id',
            'c.name AS title_name',
        ])
        ->from('el_quiz_register AS a')
        ->join('el_profile AS b', 'b.user_id', '=', 'a.user_id')
        ->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code')
        ->leftJoin('el_quiz_part AS d', 'd.id', '=', 'a.part_id')
        ->where('d.start_date', '>=', $from_date)
        ->where('d.start_date', '<=', $to_date)
        ->where('a.quiz_id', '=', $quiz_id)
        ->where('a.type', '=', 1)
        ->groupBy(['c.id', 'c.name']);

        return $query;
    }

}
