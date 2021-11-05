<?php

namespace Modules\Offline\Entities;
use App\Models\Categories\TrainingTeacher;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineSchedule
 *
 * @property int $id
 * @property int $course_id
 * @property string $start_time
 * @property string $end_time
 * @property string $lesson_date
 * @property int $teacher_main_id
 * @property int|null $teach_id
 * @property float $cost_teacher_main
 * @property float|null $cost_teach_type
 * @property int $total_lessons
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereCostTeachType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereCostTeacherMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereLessonDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereTeachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereTeacherMainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereTotalLessons($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineSchedule extends Model
{
    protected $table = 'el_offline_schedule';
    protected $fillable = [
        'course_id',
        'start_time',
        'end_time',
        'lesson_date',
        'teacher_main_id',
        'teach_id',
        'cost_teacher_main',
        'cost_teach_type',
        'total_lessons',
        'schedule_parent_id',
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

    public static function getTeacher($course_id){
        $query = TrainingTeacher::query();
        $query->select(['a.*',]);
        $query->from('el_training_teacher AS a');
        $query->leftJoin('el_offline_course_teachers AS b', 'b.teacher_id', '=', 'a.id');
        $query->where('b.course_id', '=', $course_id);
        return $query->get();
    }

    public static function getSchedules($course_id)
    {
        $query = self::query();
        $query->select([
            '*',
            /*\DB::raw('CAST(lesson_date as datetime) + CAST(start_time as datetime) as schedule_time')*/
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
