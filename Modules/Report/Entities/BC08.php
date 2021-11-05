<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class BC08 extends Model
{

    public static function sql($course)
    {
        $query =\DB::query();
        $dbprefix = DB::getTablePrefix();

        $query->select([
            'a.course_id',
            'b.id',
            'b.user_id',
            'b.code',
            'b.full_name',
            'b.title_name',
            'b.email',
            'c.score',
            'c.result',
            DB::raw("case when {$dbprefix}c.result=1 then 'X' else '' end pass"),
            DB::raw("case when NULLIF({$dbprefix}c.result,0) = 0 then 'X' else '' end fail"),
        ]);

        $query->from('el_online_register as a')
            ->join("el_profile_view as b",'b.user_id','=','a.user_id')
            ->leftJoin('el_online_result as c','c.register_id','=','a.id')
            ->where('a.course_id','=', $course)
            ->where('a.status','=',1);

        return $query;
    }

    public static function getCourseInfo($course)
    {
        return DB::table('el_online_course as a')
            ->where('a.id', '=', $course)
            ->selectRaw("code,name,start_date,end_date,null as training_location,null cost_class,course_time")
            ->first();
    }
}
