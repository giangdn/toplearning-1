<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Modules\Report\Entities\BC42;
use Modules\TrainingPlan\Entities\TrainingPlan;

class BC42Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->course_type && !$request->from_date && !$request->to_date)
            json_result([]);

        $course_type = $request->course_type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC42::sql($course_type, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            if ($row->start_date > now()){
                $row->progress = 'Chưa tới thời gian đào tạo';
            }

            if ($row->end_date){
                if ($row->end_date < now()){
                    $row->progress = 'Đã kết thúc đào tạo';
                }else{
                    $row->progress = 'Đang đào tạo';
                }
            }else{
                $row->progress = 'Đang đào tạo';
            }

            if (get_date($row->start_date, 'd') >= 1 && get_date($row->start_date, 'm') >= 10){
                $row->time = get_date($row->start_date, 'Y') . ' -> ' . (get_date($row->start_date, 'Y') + 1);
            }
            $row->course_name = $row->name;
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            if ($row->course_type == 1){
                $course = OnlineCourse::find($row->id);
                $register = OnlineRegister::where('course_id', '=', $course->id)->where('status', '=', 1)->count();
                $course_cost = OnlineCourseCost::getTotalActualAmount($course->id);
            }else{
                $course = OfflineCourse::find($row->id);
                $training_form = TrainingForm::find($course->training_form_id);
                $row->training_unit = $course->training_unit;
                $training_location = TrainingLocation::find($course->training_location_id);
                $schedule = OfflineSchedule::where('course_id', '=', $course->id)->count();
                $register = OfflineRegister::where('course_id', '=', $course->id)->where('status', '=', 1)->count();
                $course_cost = OfflineCourseCost::sumActualAmount($course->id);
            }
            $training_plan = TrainingPlan::find($course->in_plan);
            $level_subject = LevelSubject::find($course->level_subject_id);
            $object = $course->getObject();

            if ($course->unit_id){
                $arr_unit = explode(',', $course->unit_id);
                $unit = Unit::whereIn('id', $arr_unit)->pluck('name')->toArray();

                $row->unit = implode('; ', $unit);
            }

            $row->month = get_date($row->start_date, 'm');
            $row->level_subject = $level_subject ? $level_subject->name : '';
            $row->training_form = isset($training_form) ? $training_form->name : '';
            $row->in_plan = $training_plan ? 'Trong kế hoạch' : 'Ngoài kế hoạch';
            $row->plan_name = $training_plan ? $training_plan->name : '';
            $row->object = $object;
            $row->training_location = isset($training_location) ? $training_location->name : '';
            $row->num_schedule = isset($schedule) ? $schedule : '';
            $row->num_student = $register;
            $row->course_cost = $course_cost;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
