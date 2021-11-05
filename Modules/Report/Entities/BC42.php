<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class BC42 extends Model
{

    public static function sql($course_type, $from_date, $to_date)
    {
        $query = \DB::query();
        $query->from('el_course_view')
            ->select([
                'id',
                'code',
                'name',
                'start_date',
                'end_date',
                'course_type'
            ])
            ->where('status','=',1)
            ->where('start_date','>=', date_convert($from_date))
            ->where('start_date','<=', date_convert($to_date, '23:59:59'));

        if ($course_type){
            $query->where('course_type','=', $course_type);
        }

        return $query;
    }
}
