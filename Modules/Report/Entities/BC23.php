<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC23 extends Model
{
    public static function sql($training_program, $from_date, $to_date)
    {
        $sub = DB::query();
        $sub->select('id', 'course_type');
        $sub->from('el_course_view');
        $sub->where('status', '=', 1);
        $sub->where('training_program_id', '=', $training_program);
        if ($from_date && $to_date){
            $sub->where('start_date','>=', date_convert($from_date))
                ->where('start_date','<=', date_convert($to_date,'23:59:59'));
        };
        $sub->get();

        $query = DB::query();
        $query->select(['course_register.user_id'])
            ->from('el_course_register_view as course_register')
            ->joinSub($sub,'course', function ($join){
                $join->on('course_register.course_id','=','course.id');
                $join->on('course_register.course_type', '=', 'course.course_type');
            })
            ->groupBy(['course_register.user_id']);

        return $query;
    }

}
