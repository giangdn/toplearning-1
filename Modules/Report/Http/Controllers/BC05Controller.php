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
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineResult;
use Modules\Report\Entities\BC05;

class BC05Controller extends ReportController
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
        if (!$request->course_type && !$request->course)
            json_result([]);
        $course_type = $request->course_type;
        $course = $request->course;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC05::sql($course_type, $course, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $key => $row) {
            $profile = Profile::find($row->user_id);
            $arr_unit = Unit::getTreeParentUnit($profile->unit_code);
            $row->unit_name = $arr_unit ? $arr_unit[$profile->unit->level]->name : '';
            $row->parent_unit_name = $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '';
            $row->unit_name_level2 = $arr_unit ? $arr_unit[2]->name : '';

            $row->score_scorm = '';
            $activities = OnlineCourseActivity::getByCourse($row->course_id, 1);
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
                $result = OfflineResult::where('course_id', '=', $row->course_id)->where('user_id', '=', $row->user_id)->first();
                $row->result_final = ($result && $result->result == 1) ? 'Đạt' : 'Không đạt';
            }

            $row->score = $row->score > 0 ? number_format($row->score, 2) : '';
            $row->score_final = ($row->score_scorm && $row->score) ? ($row->score_scorm + $row->score)/2 : ($row->score_scorm ? $row->score_scorm : $row->score);

            $row->start_date = get_date($row->start_date);
            $row->complete_date = get_date($row->complete_date);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
