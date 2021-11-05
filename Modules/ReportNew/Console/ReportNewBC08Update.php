<?php

namespace Modules\ReportNew\Console;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\StudentCost;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\TrainingType;
use App\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineMonitoringStaff;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\ReportNew\Entities\ReportNewExportBC08;

class ReportNewBC08Update extends Command
{
    protected $signature = 'report_new_bc08:update';

    protected $description = 'report new bc08 update';
    protected $expression ="0 1 * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $offline = OfflineCourse::whereStatus(1)->get();
        foreach ($offline as $item){
            $title_join_id = $item->title_join_id ? json_decode($item->title_join_id) : [];
            $training_object_id = $item->training_object_id ? json_decode($item->training_object_id) : [];

            $total_cost = 0;
            $training_type = TrainingForm::find($item->training_form_id);
            $level_subject = LevelSubject::find($item->level_subject_id);
            $training_location = TrainingLocation::find($item->training_location_id);

            $title_join = Titles::whereIn('id', $title_join_id)->pluck('name')->toArray();
            $training_object = TrainingObject::whereIn('id', $training_object_id)->pluck('name')->toArray();
            $course_time = $item->course_time;

            $time_schedule = '';
            $schedules = OfflineSchedule::whereCourseId($item->id)->get();
            foreach ($schedules as $schedule){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }
            }

            $students_fail = $students_pass = $students_absent = $join_100 = $join_75 = $join_below_75 = 0;
            $registers = OfflineRegister::whereCourseId($item->id)->where('status', '=', 1)->get();
            foreach ($registers as $register) {
                $check = OfflineAttendance::whereRegisterId($register->id);
                if ($check->exists()) {
                    $attendance = floatval(OfflineAttendance::whereRegisterId($register->id)->avg('percent'));

                    if ($attendance == 100) {
                        $join_100 += 1;
                    }
                    if ($attendance >= 75 && $attendance < 100) {
                        $join_75 += 1;
                    }
                    if ($attendance < 75) {
                        $join_below_75 += 1;
                    }
                } else {
                    $students_absent += 1;
                }

                $user_result = OfflineResult::whereRegisterId($register->id)->where('course_id', '=', $item->id)->first();
                if ($user_result && $user_result->result == 1){
                    $students_pass += 1;
                }else{
                    $students_fail += 1;
                }
            }

            $teacher_name = OfflineTeacher::query()
                ->from('el_offline_course_teachers AS a')
                ->join('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id')
                ->where('a.course_id', '=', $item->id)
                ->whereIn('a.teacher_id', function ($sub) use ($item){
                    $sub->select(['teacher_main_id'])
                        ->from('el_offline_schedule')
                        ->where('course_id', '=', $item->id)
                        ->pluck('teacher_main_id')
                        ->toArray();
                })
                ->pluck('b.name')
                ->toArray();

            $teacher_name_note = OfflineTeacher::where('course_id', '=', $item->id)->whereNotNull('note')->pluck('note')->toArray();

            $tuteurs_name = OfflineTeacher::query()
                ->from('el_offline_course_teachers AS a')
                ->join('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id')
                ->where('a.course_id', '=', $item->id)
                ->whereIn('a.teacher_id', function ($sub) use ($item){
                    $sub->select(['teach_id'])
                        ->from('el_offline_schedule')
                        ->where('course_id', '=', $item->id)
                        ->pluck('teach_id')
                        ->toArray();
                })
                ->pluck('b.name')
                ->toArray();

            $monitoring_staff = OfflineMonitoringStaff::where('course_id', '=', $item->id)->pluck('fullname')->toArray();
            $monitoring_staff_note = OfflineMonitoringStaff::where('course_id', '=', $item->id)->whereNotNull('note')->pluck('note')->toArray();

            $off_course_cost = OfflineCourseCost::whereCourseId($item->id)->get();
            $course_cost = [];
            foreach ($off_course_cost as $cost){
                $course_cost['cost_'.$cost->cost_id] = $cost->actual_amount;

                $total_cost += $cost->actual_amount;
            }

            $student_cost = StudentCost::whereStatus(1)->get();
            $student_cost_arr = [];
            $student_cost_total = 0;
            foreach ($student_cost as $student){
                $student_cost_arr['student'.$student->id] = OfflineStudentCost::whereCostId($student->id)
                    ->whereIn('register_id', function ($sub) use ($item){
                        $sub->select(['id'])
                            ->from('el_offline_register')
                            ->where('course_id', '=', $item->id)
                            ->pluck('id')->toArray();
                    })->sum('cost');

                $student_cost_total += floatval($student_cost_arr['student'.$student->id]);
            }
            $student_cost_arr['student_cost_total'] = $student_cost_total;

            $total_cost += $student_cost_total;

            ReportNewExportBC08::query()->updateOrCreate([
                'course_id' => $item->id,
            ],[
                'course_code' => $item->code,
                'course_name' => $item->name,
                'lecturer' => count($teacher_name) > 0 ? implode('; ', $teacher_name) : '',
                'tuteurs' => count($tuteurs_name) > 0 ? implode('; ', $tuteurs_name) : '',
                'training_form_name' => 'Đào tạo tập trung',
                'training_type_id' => @$training_type->id,
                'training_type_name' => @$training_type->name,
                'level_subject' => @$level_subject->name,
                'training_location' => @$training_location->name,
                'training_unit' => $item->training_unit,
                'title_join' => (count($title_join) > 0 ? implode('; ', $title_join) : ''),
                'training_object' => (count($training_object) > 0 ? implode('; ', $training_object) : ''),
                'course_time' => $course_time,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'time_schedule' => $time_schedule,
                'created_by' => @Profile::find($item->created_by)->full_name,
                'registers' => $registers->count(),
                'join_100' => $join_100,
                'join_75' => $join_75,
                'join_below_75' => $join_below_75,
                'students_absent' => $students_absent,
                'students_pass' => $students_pass,
                'students_fail' => $students_fail,
                'course_cost' => json_encode($course_cost),
                'student_cost' => json_encode($student_cost_arr),
                'total_cost' => $total_cost,
                'recruits' => ($item->course_employee == 1 ? 1 : 0),
                'exist' => ($item->course_employee == 2 ? 1 : 0),
                'plan' => ($item->course_action == 1 ? 1 : 0),
                'incurred' => ($item->course_action == 2 ? 1 : 0),
                'monitoring_staff' => (count($monitoring_staff) > 0 ? implode('; ', $monitoring_staff) : ''),
                'monitoring_staff_note' => (count($monitoring_staff_note) > 0 ? implode('; ', $monitoring_staff_note) : ''),
                'teacher_note' => (count($teacher_name_note) > 0 ? implode('; ', $teacher_name_note) : ''),
                'unit_by' => $item->unit_by,
            ]);
        }
    }
}
