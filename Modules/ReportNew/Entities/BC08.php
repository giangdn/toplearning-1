<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

class BC08 extends Model
{
    public static function sql($from_date, $to_date, $training_type_id, $title_id)
    {
        ReportNewExportBC08::addGlobalScope(new CompanyScope());
        $query = ReportNewExportBC08::query();
        $query->where('start_date', '>=', date_convert($from_date));
        $query->where('end_date', '<=', date_convert($to_date, '23:59:59'));

        if ($training_type_id){
            $query->whereIn('training_type_id', explode(',', $training_type_id));
        }
        if ($title_id){
            $query->whereIn('course_id', function ($sub) use ($title_id) {
                $sub->select(['course_id'])
                    ->from('el_offline_object')
                    ->whereIn('title_id', explode(',', $title_id))
                    ->pluck('course_id')->toArray();
            });
        }

        return $query;
    }

}
