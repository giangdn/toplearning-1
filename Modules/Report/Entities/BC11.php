<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Rating\Entities\RatingCourse;

class BC11 extends Model
{
    public static function sql($course, $type)
    {
        $sub = DB::query();
        $sub->from('el_rating_course')
            ->where('course_id','=', $course)
            ->where('type', '=', $type)
            ->where('send', '=', 1)
            ->groupBy(['template_id'])
            ->select(['template_id']);

        $query = DB::query();
        $query->select(['c.id', 'c.name', 'a.template_id'])
            ->from('el_rating_category as a')
            ->joinSub($sub,'b', function ($join){
                $join->on('a.template_id', '=', 'b.template_id');
            })
            ->leftJoin('el_rating_question as c', 'c.category_id', '=', 'a.id');

        return $query;
    }
}
