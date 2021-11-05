<?php

namespace Modules\Report\Entities;

use App\Models\Categories\TrainingTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourse;

class BC06 extends Model
{

    public static function sql($from_date, $to_date, $teacher, $teacher_type)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $sub = OfflineSchedule::query()
            ->selectRaw('course_id, teacher_main_id, SUM(total_lessons) AS lesson, SUM(cost_teacher_main) AS cost')
            ->from('el_offline_schedule')
            ->where('lesson_date','>=', $from_date)
            ->where('lesson_date','<=', $to_date)
            ->groupBy(['course_id','teacher_main_id']);
        if($teacher){
            $sub->where('teacher_main_id', '=', $teacher);
        }

        $query = \DB::query();
        $query->from('el_training_teacher as a')
            ->joinSub($sub, 'b', function ($join){
                $join->on('a.id', '=', 'b.teacher_main_id');
            })
            ->join('el_offline_course as c', 'b.course_id', '=', 'c.id')
            ->leftJoin('el_training_location as d', 'd.id', '=', 'c.training_location_id')
            ->where('c.status', '=', 1)
            ->where('a.type', '=', $teacher_type)
            ->select([
                'a.id',
                'a.code AS code',
                'a.name as teacher',
                'c.code AS course_code',
                'c.name AS course_name',
                'c.start_date',
                'c.end_date',
                'b.*',
                'd.name AS training_location'
            ]);

        return $query;
    }
}
