<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class BC43 extends Model
{

    public static function sql($course_id,$course_type)
    {
        if ($course_type==1)
            $course = OnlineCourse::find($course_id);
        else
            $course = OfflineCourse::find($course_id);
        $query = \DB::query()->select('a.id', 'a.user_id','a.reality_manager','a.reason_reality_manager','a.result','b.code','b.firstname','b.lastname', 'c.name as title')
            ->from('el_plan_app as a')
            ->join('el_profile as b','a.user_id','b.user_id')
            ->join('el_titles as c','c.code','b.title_code')
            ->where(['a.course_id'=>$course_id,'a.course_type'=>$course_type,'a.status'=>5])
        ->addSelect(\DB::raw("'$course->name' as course_name"))
        ->addSelect(\DB::raw("'$course->start_date' as start_date"))
        ->addSelect(\DB::raw("'$course->end_date' as end_date"))
        ->addSelect(\DB::raw("'$course->training_unit' as training_unit"));

        return $query;
    }
}
