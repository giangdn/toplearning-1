<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineSchedule;

class BC10 extends Model
{
    public static function sql($subject_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id, $area_id)
    {
        $shedule = DB::query()
            ->select(['a.course_id', 'b.user_id'])
            ->from('el_offline_schedule as a')
            ->leftJoin('el_offline_register as b', 'b.course_id', '=', 'a.course_id')
            ->leftJoin('el_offline_course as c', 'c.id', '=', 'a.course_id');
        if ($subject_id) {
            $shedule->whereIn('c.subject_id', explode(',', $subject_id));
        }
        if ($from_date){
            $shedule->where('c.start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $shedule->where('c.end_date', '<=', date_convert($to_date, '23:59:59'));
        }

        $shedule->whereNotExists(function (Builder $builder) {
            $builder->select(['id'])
                ->from('el_offline_attendance as att')
                ->whereColumn('att.schedule_id', '=', 'a.id')
                ->whereColumn('att.user_id', '=', 'b.user_id')
                ->whereColumn('att.course_id', '=', 'b.course_id');
        });
        $list_shedule = $shedule->get();

        $user_arr = [];
        $course_arr = [];
        foreach ($list_shedule as $item){
            $user_arr[] = $item->user_id;
            $course_arr[] = $item->course_id;
        }

        OfflineRegisterView::addGlobalScope(new DraftScope('user_id'));
        $query = OfflineRegisterView::query();
        $query->select([
            'el_offline_register_view.id',
            'el_offline_register_view.user_id',
            'el_offline_register_view.course_id',
            'el_offline_register_view.note',
            'c.name as unit_type_name',
            'd.name as area_name_unit'
        ]);
        $query->from('el_offline_register_view');
        $query->leftJoin('el_offline_course as course', 'course.id', '=', 'el_offline_register_view.course_id');
        $query->leftjoin('el_unit as b','b.id','=','el_offline_register_view.unit_id');
        $query->leftjoin('el_unit_type as c','c.id','=','b.type');
        $query->leftjoin('el_area as d','d.id','=','b.area_id');
        $query->where('el_offline_register_view.status', '=', 1);
        $query->where(function ($sub) use ($user_arr, $course_arr){
            $sub->orWhereNotExists(function (Builder $builder) {
                $builder->select(['id'])
                    ->from('el_offline_course_complete as occ')
                    ->whereColumn('occ.user_id', '=', 'el_offline_register_view.user_id')
                    ->whereColumn('occ.course_id', '=', 'el_offline_register_view.course_id');
            });
            $sub->orWhere(function ($sub2) use ($user_arr, $course_arr){
                $sub2->whereIn('el_offline_register_view.course_id', $course_arr);
                $sub2->whereIn('el_offline_register_view.user_id', $user_arr);
            });
        });

        if ($subject_id){
            $query->whereIn('course.subject_id', explode(',', $subject_id));
        }
        if ($from_date){
            $query->where('course.start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('course.end_date', '<=', date_convert($to_date, '23:59:59'));
        }
        if ($training_type_id){
            $query->whereIn('course.training_type_id', explode(',', $training_type_id));
        }
        if ($area_id) {
            $area = Area::find($area_id);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('d.id', $area_id);
                $sub_query->orWhere('d.id', '=', $area->id);
            });
        }
        if ($title_id){
            $query->whereIn('el_offline_register_view.title_id', explode(',', $title_id));
        }
        if ($unit_id){
            $unit = Unit::find($unit_id);
            $unit_child = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_child, $unit) {
                $sub_query->orWhereIn('el_offline_register_view.unit_id', $unit_child);
                $sub_query->orWhere('el_offline_register_view.unit_id', '=', $unit->id);
            });
        }

        return $query;
    }

}
