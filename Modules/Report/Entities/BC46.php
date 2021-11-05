<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;

class BC46 extends Model
{
    public static function sql($from_date, $to_date, $quiz_template_id)
    {
        $dbprefix = \DB::getTablePrefix();
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $query = Quiz::query();
        $query->select([
            'a.*',
        ])
            ->from('el_quiz AS a');

        if ($from_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'a.id)'), '>=', $from_date);
        }

        if ($to_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'a.id)'), '<=', $to_date);
        }

        if ($quiz_template_id){
            $query->where('a.quiz_template_id', '=', $quiz_template_id);
        }

        return $query;
    }

}
