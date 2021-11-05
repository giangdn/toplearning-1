<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ReportNew\Entities\BC18
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BC18 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC18 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC18 query()
 * @mixin \Eloquent
 */
class BC18 extends Model
{
    protected $table = 'el_report_bc18';
    protected $fillable=[
        'id',
        'user_id',
        'user_code',
        'full_name',
        'email',
        'phone',
        'area_id',
        'area',
        'unit_id',
        'unit1_id',
        'unit1_code',
        'unit1_name',
        'unit2_id',
        'unit2_code',
        'unit2_name',
        'unit3_id',
        'unit3_code',
        'unit3_name',
        'position_id',
        'position_name',
        'titles_id',
        'titles_name',
        'training_program_id',
        'training_program_name',
        'subject_id',
        'subject_name',
        'course_id',
        'course_code',
        'course_name',
        'training_unit',
        'training_type_id',
        'training_type',
        'training_address',
        'course_time',
        'start_date',
        'end_date',
        'time_schedule',
        'cost_held',
        'cost_training',
        'cost_external',
        'cost_teacher',
        'cost_student',
        'cost_total',
        'time_commit',
        'from_time_commit',
        'to_time_commit',
        'time_rest',
        'cost_refund',
        'note',
    ];
    public static function sql($title_id,$unit_id,$area_id,$training_type_id, $from_date, $to_date)
    {
        BC18::addGlobalScope(new DraftScope('user_id'));
        $query = \DB::table('el_report_bc18')
            ->select('*');
        if ($from_date){
            $query->where('start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('end_date', '<=', date_convert($to_date));
        }
        if ($title_id){
            $query->where('titles_id', $title_id );
        }
        if ($unit_id){
            $query->where('unit_id', '=', $unit_id);
        }
        if ($area_id){
            $query->where('area_id', '=', $area_id);
        }
        if ($training_type_id){
            $query->where('training_type_id', '=', $training_type_id);
        }
        return $query;
    }
}
