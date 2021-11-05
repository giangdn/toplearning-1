<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Subject;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

class BC29 extends Model
{
    public static function sql($year)
    {
        Subject::addGlobalScope(new DraftScope());
        $subjects_arr = Subject::where('status',1)->pluck('id')->toArray();

        $query = ReportNewExportBC26::query();
        $query->whereIn('subject_id', $subjects_arr);
        $query->where('year', '=', $year);

        return $query;
    }

}
