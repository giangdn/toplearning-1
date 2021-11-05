<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\ReportNew\Entities\BC23;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\User\Entities\UserCompletedSubject;

class BC23Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        if ($request->title_id){
            $levelSubjects = TrainingRoadmap::whereTitleId($request->title_id)->orderBy('id')->get();
            $title = Titles::find($request->title_id);
        }else{
            $levelSubjects = '';
            $title = '';
        }

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'levelSubjects' => $levelSubjects,
            'title' => $title,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $title_id = $request->title_id;
        $isSubmit = $request->isSubmit;
        if (!$title_id)
            return;

        $query = BC23::sql($title_id);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $training_roadmap = TrainingRoadmap::whereTitleId($title_id)->orderBy('id')->get();
            foreach ($training_roadmap as $roadmap){
                $user_complete = UserCompletedSubject::query()
                    ->from('el_user_completed_subject as a')
                    ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id')
                    ->where('b.title_id', '=', $title_id)
                    ->where('a.subject_id', '=', $roadmap->subject_id)
                    ->count();

                $row->{'num_'.$roadmap->subject_id} = $user_complete;
                $row->{'rate_'.$roadmap->subject_id} = number_format(($user_complete/($row->employees > 0 ? $row->employees : 1))*100, 2);
            }

            /*$levelSubjects = BC23::getRateComplete($title_id);
            foreach ($levelSubjects as $index => $levelSubject) {
                $row->{'num_'.$levelSubject->code} = $levelSubject->user_finish;
                $row->{'rate_'.$levelSubject->code} = round(($levelSubject->user_finish/$row->employees)*100,2);
            }*/
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

}
