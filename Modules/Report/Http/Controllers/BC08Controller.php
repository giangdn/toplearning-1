<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineResult;
use Modules\Report\Entities\BC08;

class BC08Controller extends ReportController
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
        if (!$request->course)
            json_result([]);
        $course = $request->course;
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC08::sql($course);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row){
            $profile = Profile::find($row->user_id);
            $arr_unit = Unit::getTreeParentUnit($profile->unit_code);
            $row->unit_name = $arr_unit ? $arr_unit[$profile->unit->level]->name : '';
            $row->parent_unit_name = $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '';
            $row->unit_name_level2 = $arr_unit ? $arr_unit[2]->name : '';

            $activities = OnlineCourseActivity::getByCourse($row->course_id, 1);
            $scorm = 0;
            if (count($activities) > 0){
                foreach ($activities as $activity){
                    $activity_scorm = OnlineCourseActivityScorm::find($activity->subject_id);
                    $score = $activity_scorm->getScoreScorm($row->user_id);
                    $scorm += $score;
                }
            }
            $row->score_scorm = number_format($scorm/(count($activities) > 0 ? count($activities) : 1), 2);
            $row->score = number_format($row->score, 2);
            $row->score_final = number_format($row->score_scorm ? ($row->score_scorm + $row->score)/2 : $row->score, 2);

            if ($row->result == 1){
                $row->pass = 'X';
            }else{
                $row->fail = 'X';
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
