<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class BC05 extends Model
{

    public static function sql($course_type,$course, $from_date, $to_date)
    {
        $query = \DB::query();
        $query->from('el_course_register_view as a')
            ->join('el_course_view as b',function ($join) use($course_type,$course){
                $join->on('a.course_id', '=', DB::raw($course));
                $join->on('a.course_type', '=', DB::raw($course_type));
            })
            ->join('el_profile_view as c','a.user_id','=','c.user_id')
            ->leftJoin('el_course_complete as e', function ($join){
                $join->on('e.course_id', '=', 'b.course_id');
                $join->on('e.course_type', '=', 'b.course_type');
                $join->on('e.user_id', '=', 'a.user_id');
            })
            ->where('a.status','=',1)
            ->where('b.course_id','=', $course)
            ->where('b.course_type','=', $course_type);
        if ($from_date && $to_date){
            $query->where('b.start_date','>=', date_convert($from_date))
                ->where('b.start_date','<=', date_convert($to_date, '23:59:59'));
        }
        $query->select([
            'a.id',
            'a.user_id',
            'a.score',
            'a.note',
            'a.course_id',
            'a.course_type as type',
            'b.start_date',
            'b.code as course_code',
            'b.end_date',
            'c.code',
            'c.full_name',
            'c.title_name',
            'e.updated_at as complete_date'
        ]);

        return $query;
    }

    public static function getCourseInfo($course,$course_type)
    {
        if ($course_type == 1){
            return DB::table(DB::raw('mdl_el_online_course as a'))
                ->where('id', '=', $course)
                ->selectRaw("name,course_time,start_date,end_date,null as training_unit,N'Offline' as course_type")
                ->first();
        }elseif ($course_type == 2) {
            return DB::table(DB::raw('mdl_el_offline_course as a'))
                ->where('id', '=', $course)
                ->selectRaw("name,course_time,start_date,end_date,training_unit,N'Tập trung' as course_type")
                ->first();
        }
    }
}
