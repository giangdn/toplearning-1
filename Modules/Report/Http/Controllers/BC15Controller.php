<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\TrainingForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineResult;
use Modules\Report\Entities\BC15;
use function PHPSTORM_META\elementType;

class BC15Controller extends ReportController
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
        if (!$request->from_date && !$request->to_date)
            json_result([]);

        $unit_id = $request->unit_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC15::sql($unit_id, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy('course.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $scorm = [];
           if ($row->course_type == 1){
               $onl_result = OnlineResult::where('course_id', '=', $row->id)->where('user_id', '=', $row->user_id)
                   ->where('result', '=', 1)->first();

               if ($onl_result){
                   $row->result_achieved = 'x';
               }else{
                   $row->result_not_achieved = 'x';
               }

               $row->course_cost = number_format(OnlineCourseCost::where('course_id', '=', $row->id)->sum('actual_amount'), 0, ',', '.');

               $score_scorm = scoreScorm($row->user_id, $row->course_code);
               $activities = OnlineCourseActivity::getByCourse($row->id);
               foreach ($activities as $activity){
                   if ($activity->activity_id == 1){
                       $scorm[] = ($score_scorm && $score_scorm->finalgrade > 0) ? number_format($score_scorm->finalgrade, 2) : '';
                   }
               }
           }else{
               $register_id = OfflineRegister::where('course_id', '=', $row->id)->pluck('id')->toArray();
               $student_cost = OfflineStudentCost::whereIn('register_id', $register_id)->sum('cost');
               $course_cost = OfflineCourseCost::where('course_id', '=', $row->id)->sum('actual_amount');

               $row->course_cost = number_format($course_cost + $student_cost,0, ',', '.');

               $off_result = OfflineResult::where('course_id', '=', $row->id)
                   ->where('user_id', '=', $row->user_id)
                   ->where('result', '=', 1)
                   ->first();

               if ($off_result){
                   $row->result_achieved = 'x';
               }else{
                   $row->result_not_achieved = 'x';
               }
               $off_course = OfflineCourse::find($row->id);
               $training_form = TrainingForm::find($off_course->training_form_id);

               $row->training_form = $training_form ? $training_form->name : '';

               $indemnify = Indemnify::getCommitAmount($row->user_id, $row->id);

               $row->indem = ($indemnify ? number_format($indemnify->commit_amount - $indemnify->exemption_amount, 0, ',', '.') : '0') . ' VND';
           }

            $row->score_scorm = count($scorm) > 0 ? implode(' ', $scorm) : '';
            $row->score = $row->score > 0 ? number_format($row->score, 2) : '';
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->course_type = $row->course_type == 1 ? 'Trực truyến' : 'Tập trung';
            $row->full_name = $row->lastname .' '. $row->firstname;

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
