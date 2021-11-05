<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC13 extends Model
{
    public static function sql($type=null, $from_date, $to_date)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');
        $sub = DB::query();
        $sub->from('el_course_view');
            if($type){
                $sub->where('course_type', '=', $type);
            }
        $sub->where('status', '=', 1)
            ->where('start_date','>=', $from_date)
            ->where('start_date','<=', $to_date)
            ->select('id', 'code', 'name', 'course_type', 'action_plan', 'start_date', 'end_date')
            ->get();

        $query = DB::query();
        $query->select([
                'course_register.id',
                'course_register.course_id',
                'course_register.course_type',
                'course.code AS course_code',
                'course.name AS course_name',
                'course.action_plan',
                'course.start_date',
                'course.end_date',
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
            ->leftJoin('el_unit AS unit','unit.code','=','profile.unit_code')
            ->whereIn('course_register.id', function ($onl){
                $onl->select('onl_result.register_id')
                    ->from('el_online_result AS onl_result')
                    ->whereColumn('onl_result.course_id', '=', 'course_register.course_id')
                    ->whereColumn('onl_result.user_id', '=', 'course_register.user_id')
                    ->where('onl_result.result', '!=', 1);
            })
            ->orWhereIn('course_register.id', function ($off){
                $off->select('off_result.register_id')
                    ->from('el_offline_result AS off_result')
                    ->whereColumn('off_result.course_id', '=', 'course_register.course_id')
                    ->whereColumn('off_result.user_id', '=', 'course_register.user_id')
                    ->where('off_result.result', '!=', 1);
            })
            ->orWhere('profile.user_id', '=', function ($plan_app){
                $plan_app->select('plan_app.user_id')
                    ->from('el_plan_app AS plan_app')
                    ->whereColumn('plan_app.course_id', '=', 'course_register.course_id')
                    ->whereColumn('plan_app.course_type', '=', 'course_register.course_type')
                    ->where('plan_app.status', '<', 1);
            });

        return $query;
    }

}
