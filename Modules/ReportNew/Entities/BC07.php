<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\TrainingProcess;

class BC07 extends Model
{
    public static function sql($user_id, $from_date, $to_date, $unit_id, $area_id)
    {
        Subject::addGlobalScope(new DraftScope());
        $subject_arr = Subject::whereStatus(1)->pluck('id')->toArray();

        $query = TrainingProcess::query();
        $query->select(['el_training_process.*']);
        $query->from('el_training_process');
        $query->whereIn('el_training_process.subject_id', $subject_arr);

        if ($unit_id || $area_id){
            $query->leftJoin('el_profile as profile', 'profile.user_id', '=', 'el_training_process.user_id');
            $query->leftJoin('el_unit as unit', 'unit.code', '=', 'profile.unit_code');
        }

        if ($user_id){
            $query->whereIn('el_training_process.user_id', explode(',', $user_id));
        }
        if ($from_date){
            $query->where('el_training_process.start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('el_training_process.end_date', '<=', date_convert($to_date, '23:59:59'));
        }
        if ($area_id) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'unit.area_id');
            $area = Area::find($area_id);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit_id){
            $unit = Unit::find($unit_id);
            $unit_child = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_child, $unit) {
                $sub_query->orWhereIn('unit.id', $unit_child);
                $sub_query->orWhere('unit.id', '=', $unit->id);
            });
        }

        return $query;
    }

}
