<?php

namespace Modules\ReportNew\Entities;

use App\CourseView;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\RattingCourse;

class BC30 extends Model
{
    public static function sql($course_type, $subject_id, $from_date, $to_date)
    {
        CourseView::addGlobalScope(new CompanyScope());
        $course_view_arr = CourseView::whereStatus(1)->whereIsopen(1)->pluck('id')->toArray();

        $query = RattingCourse::query();
        $query->select([
            'a.*',
            'b.name as course_name',
            'b.code as course_code',
            'b.start_date as start_date',
            'b.end_date as end_date',
        ]);
        $query->from('el_ratting_course as a');
        $query->leftJoin('el_course_view as b','b.course_id','=','a.course_id');
        $query->where('a.type', '=', $course_type);
        $query->where('b.course_type', '=', $course_type);
        $query->whereIn('b.id', $course_view_arr);

        if ($subject_id){
            $query->whereIn('b.subject_id', explode(',', $subject_id));
        }

        if ($from_date){
            $query->where('b.start_date', '>=', date_convert($from_date, '00:00:00'));
        }

        if ($to_date){
            $query->where('b.end_date', '<=', date_convert($to_date, '23:59:59'));
        }

        return $query;
    }
}
