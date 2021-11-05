<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\TrainingForm;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineResult;
use Modules\Report\Entities\BC02;

class BC02Controller extends ReportController
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
        if (!$request->date)
            json_result([]);
        $month = Str::before($request->date, '/') ;
        $year = Str::after($request->date, '/');
        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC02::sql($month,$year);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->score_scorm = '';
            $activities = OnlineCourseActivity::getByCourse($row->course_id, 1);
            $training_form = '';
            if ($row->type == 1){ // Trực tuyến
                $scorm = 0;
                if (count($activities) > 0){
                    foreach ($activities as $activity){
                        $activity_scorm = OnlineCourseActivityScorm::find($activity->subject_id);
                        $score = $activity_scorm->getScoreScorm($row->user_id);
                        $scorm += $score;
                    }
                }
                $row->score_scorm = number_format($scorm/(count($activities) > 0 ? count($activities) : 1), 2);
                $result = OnlineResult::where('course_id', '=', $row->course_id)->where('user_id', '=', $row->user_id)->first();
                $row->result_final = ($result && $result->result == 1) ? 'Đạt' : 'Không đạt';
            }else{
                $offline = OfflineCourse::find($row->course_id);
                $training_form = TrainingForm::find($offline->training_form_id);

                $result = OfflineResult::where('course_id', '=', $row->course_id)->where('user_id', '=', $row->user_id)->first();
                $row->result_final = ($result && $result->result == 1) ? 'Đạt' : 'Không đạt';
            }

            $row->score = $row->score > 0 ? number_format($row->score, 2) : '';
            $row->score_final = ($row->score_scorm && $row->score) ? ($row->score_scorm + $row->score)/2 : ($row->score_scorm ? $row->score_scorm : $row->score);

            $row->training_form = $training_form ? $training_form->name : '';

            $profile = Profile::find($row->user_id);
            $arr_unit = Unit::getTreeParentUnit($profile->unit_code);
            $row->unit_name = $arr_unit ? $arr_unit[$profile->unit->level]->name : '';
            $row->parent_unit_name = $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '';
            $row->unit_name_level2 = $arr_unit ? $arr_unit[2]->name : '';

            $row->fullname = $row->lastname . ' ' . $row->firstname;

            $course_time = preg_replace("/[^0-9]./", '', $row->course_time);
            $course_time_unit = preg_replace("/[^a-z]/", '', $row->course_time);
            switch ($course_time_unit){
                case 'day': $time_unit = 'Ngày'; break;
                case 'session': $time_unit = 'Buổi'; break;
                default : $time_unit = 'Giờ'; break;
            }
            $row->course_time = $course_time ? $course_time . ' ' . $time_unit : '';
            $row->commit_date = get_date($row->commit_date, 'd/m/Y');
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');

            $row->teacher = OfflineTeacher::getTeachers($row->course_id);

            $indemnify = Indemnify::where('user_id', '=', $row->user_id)->where('course_id', '=', $row->course_id)->first();
            $row->commit_month = $indemnify ? $indemnify->commit_date : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
