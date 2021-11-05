<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Entities\QuizRegister;

class BC27 extends Model
{
    public static function sql($quiz_id, $from_date, $to_date, $user_id)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $query = QuizRegister::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.code',
            'b.email',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.name as part_name',
            'g.grade',
        ]);
        $query->from('el_quiz_register AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_quiz_part AS e', 'e.id', '=', 'a.part_id');
        $query->leftJoin('el_quiz_result AS g', function ($join){
            $join->on('g.quiz_id', '=', 'a.quiz_id')
                ->on('g.user_id', '=', 'a.user_id')
                ->on('g.type', '=', 'a.type');
        });
        $query->where('e.start_date','>=', $from_date)
            ->where('e.start_date','<=', $to_date)
            ->where('a.quiz_id', '=', $quiz_id)
            ->where('a.user_id', '=', $user_id);

        return $query;
    }

}
