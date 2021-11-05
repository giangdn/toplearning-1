<?php

namespace Modules\CoursePlan\Entities;

use App\Models\Categories\TrainingTeacher;
use Illuminate\Database\Eloquent\Model;

class CoursePlanschedule extends Model
{
    protected $table = 'el_course_plan_schedule';
    protected $fillable = [
        'course_id',
        'course_type',
        'start_time',
        'end_time',
        'lesson_date',
        'teacher_main_id',
        'teach_id',
        'cost_teacher_main',
        'cost_teach_type',
        'total_lessons',

    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => 'Khóa học',
            'start_time' => 'Thời gian bắt đầu',
            'end_time' => 'Thời gian kết thúc',
            'lesson_date' => 'Ngày học',
            'teacher_main_id' => 'Giảng viên chính',
            'teach_id' => 'Trợ giảng',
            'cost_teacher_main' => 'Chi phí giảng viên chính',
            'cost_teach_type' => 'Chi phí trợ giảng',
            'total_lessons' => 'Số tiết',
        ];
    }

    public static function getTeacher($course_type, $course_id){
        $query = TrainingTeacher::query();
        $query->select(['a.*']);
        $query->from('el_training_teacher AS a');
        $query->leftJoin('el_course_plan_teacher AS b', 'b.teacher_id', '=', 'a.id');
        $query->where('b.course_id', '=', $course_id);
        $query->where('b.course_type', '=', $course_type);
        return $query->get();
    }

    public static function getSchedules($course_id)
    {
        $query = self::query();
        $query->select([
            '*',
        ]);
        $query->where('course_id','=',$course_id);
        $query->orderBy('lesson_date');
        $query->orderBy('start_time');
        return $query->get();
    }
    public static function getMinSchedules($course_id)
    {
        $min_lesson_date = self::where('course_id','=',$course_id)->selectRaw('MIN(CAST(lesson_date as datetime) + CAST(start_time as datetime)) as schedule_id')->value('schedule_id');
        $schedule_id = self::where('course_id', '=', $course_id)
            ->whereRaw("(CAST(lesson_date as datetime) + CAST(start_time as datetime))='".$min_lesson_date."'")->value('id');
        return $schedule_id;
    }
}
