<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Modules\Online\Entities\OnlineCourse;

/**
 * Modules\ReportNew\Entities\BC21
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BC21 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC21 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC21 query()
 * @mixin \Eloquent
 */
class BC21 extends Model
{
    public static function sql()
    {
        OnlineCourse::addGlobalScope(new DraftScope());
        $query = \DB::table('el_online_course')
            ->join('el_profile_view as b','el_online_course.created_by','=','b.user_id')
            ->select('el_online_course.id','el_online_course.code','el_online_course.name','el_online_course.start_date','el_online_course.end_date','b.full_name as created_user')
            ->where(['el_online_course.status'=>1,'el_online_course.isopen'=>1]);
        return $query;
    }
}
