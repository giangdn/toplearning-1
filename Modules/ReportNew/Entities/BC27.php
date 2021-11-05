<?php

namespace Modules\ReportNew\Entities;

use App\CourseView;
use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC27 extends Model
{
    public static function sql($course_type, $from_date, $to_date)
    {
        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->where('status', '=', 1);

        if ($course_type){
            $query->where('course_type', '=', $course_type);
        }
        if ($from_date){
            $query->where('start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where(function ($sub) use ($to_date){
                $sub->orWhereNull('end_date');
                $sub->orWhere('end_date', '<=', date_convert($to_date, '23:59:59'));
            });
        }

        return $query;
    }
}
