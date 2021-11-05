<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC12 extends Model
{
    public static function sql($course, $type)
    {
        $query = DB::query();
        $query->select([
                'a.id',
                'a.note',
                'b.user_id',
                'b.code',
                'b.lastname',
                'b.firstname',
                'b.gender',
                'b.email',
                'c.name AS title_name',
            ])
            ->from('el_course_register_view as a')
            ->leftJoin('el_profile as b','b.user_id','=','a.user_id')
            ->leftJoin('el_titles as c','c.code','=','b.title_code')
            ->where('a.course_id','=', $course)
            ->where('a.course_type', '=', $type)
            ->where('a.status', '=', 1);

        return $query;
    }
}
