<?php

namespace Modules\Report\Entities;

use App\Permission;
use App\Models\Categories\UnitManager;
use Illuminate\Database\Eloquent\Model;

class BC02 extends Model
{

    public static function sql($month,$year)
    {

        $query = \DB::query();
        $query->from('el_course_register_view as a')
            ->join('el_course_view as b',function ($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->join('el_profile_view as c', 'a.user_id','=','c.user_id')
            ->where('a.status','=',1);
        $query->select([
                'a.id',
                'a.course_id',
                'a.user_id',
                'a.course_type as type',
                'a.score',
                'a.note',
                'c.code',
                'c.lastname',
                'c.firstname',
                'c.title_name',
                'b.code as course_code',
                'b.name AS course_name',
                \DB::raw("CASE WHEN ".\DB::getTablePrefix()."b.course_type=1 THEN N'Offline' ELSE N'Tập trung' END course_type"),
                'b.course_time',
                'b.training_unit',
                'b.commit_date',
                'b.start_date',
                'b.end_date',
            ]);
        if ($month && $year){
            $query->where(\DB::raw('month(start_date)'),'=',$month);
            $query->where(\DB::raw('year(start_date)'),'=',$year);
        }
        return $query;
    }
}
