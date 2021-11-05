<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;

class BC10 extends Model
{
    public static function sql($from_date,$to_date,$type=null)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');
        $query = DB::query();

        $sub = DB::query();
        $sub->from('el_offline_schedule')
            ->groupBy(['course_id','teacher_main_id'])
            ->selectRaw('course_id, teacher_main_id');

        $query->select([
            'a.id',
            'a.code AS course_code',
            'a.name AS course_name',
            'a.start_date',
            'a.end_date',
            'a.training_unit',
            'a.course_time',
            'b.name AS training_form_name',
            'd.code AS teacher_code',
            'd.name AS teacher_name',
            'd.email',
            'd.phone',
            'f.name AS title_name',
            'e.user_id',
        ])
            ->from('el_offline_course AS a')
            ->leftJoin('el_training_form AS b', 'b.id','=', 'a.training_form_id')
            ->joinSub($sub,'c', function ($join){
                $join->on('a.id', '=', 'c.course_id');
            })
            ->leftJoin('el_training_teacher AS d', 'd.id', '=', 'c.teacher_main_id')
            ->leftJoin('el_profile AS e', 'e.user_id', '=', 'd.user_id')
            ->leftJoin('el_titles AS f', 'f.code', '=', 'e.title_code')
            ->leftJoin('el_unit AS g', 'g.code', '=', 'e.unit_code')
            ->where('a.start_date','>=', $from_date)
            ->where('a.start_date','<=', $to_date)
            ->where('a.status', '=', 1);
            if ($type)
                $query->where('d.type', '=', $type);

        return $query;
    }
}
