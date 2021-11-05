<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC15 extends Model
{
    public static function sql($unit_id = null, $from_date, $to_date)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');
        $sub = DB::query();
        $sub->from('el_course_view');
        $sub->where('status', '=', 1)
            ->where('start_date','>=', $from_date)
            ->where('start_date','<=', $to_date)
            ->select('id', 'code', 'name', 'course_type', 'start_date', 'end_date', 'training_unit')
            ->get();

        $query = DB::query();
        $query->select([
            'course.id',
            'course_register.course_id',
            'course_register.course_type',
            'course_register.score',
            'course.code AS course_code',
            'course.name AS course_name',
            'course.start_date',
            'course.end_date',
            'course.training_unit',
            'profile.user_id',
            'profile.code',
            'profile.lastname',
            'profile.firstname',
            'title.name AS title_name',
            'unit.name AS unit_name',
        ])
            ->from('el_course_register_view as course_register')
            ->joinSub($sub,'course', function ($join){
                $join->on('course_register.course_id','=','course.id');
                $join->on('course_register.course_type', '=', 'course.course_type');
            })
            ->leftJoin('el_profile as profile','profile.user_id','=','course_register.user_id')
            ->leftJoin('el_titles as title','title.code','=','profile.title_code')
            ->leftJoin('el_unit AS unit','unit.code','=','profile.unit_code');
        if ($unit_id){
            $query->where('unit.id', '=', $unit_id);
        }

        return $query;
    }

}
