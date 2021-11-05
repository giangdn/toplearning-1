<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Rating\Entities\RatingCourse;

class BC09 extends Model
{
    public static function sql($course, $type)
    {
        $query = RatingCourse::query();
        $query->select([
                'a.id',
                'a.send',
                'a.type',
                'a.user_type',
                'a.user_id',
                'b.code',
                'b.lastname',
                'b.firstname',
                'c.name AS title_name',
                'd.name AS unit_name',
                'e.name AS secondary_name',
                'e.code AS user_secon_code',
                'e.email AS user_secon_email',
            ])
            ->from('el_rating_course as a')
            ->leftJoin('el_profile AS b', function ($join) {
                $join->on('b.user_id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 1);
            })
            ->leftJoin('el_titles as c','c.code','=','b.title_code')
            ->leftJoin('el_unit AS d','d.code','=','b.unit_code')
            ->leftJoin('el_quiz_user_secondary AS e', function ($join){
                $join->on('e.id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 2);
            })
            ->where('a.course_id','=', $course)
            ->where('a.type', '=', $type)
            ->where('a.send', '=', 1);

        return $query;
    }
}
