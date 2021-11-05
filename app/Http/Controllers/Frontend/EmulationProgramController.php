<?php

namespace App\Http\Controllers\Frontend;

use App\EmulationProgram;
use App\EmulationProgramObject;
use App\EmulationProgramCondition;
use App\ArmorialEmulationProgram;
use App\EmulationUserGetArmorial;
use App\Imports\EmulationUserImport;
use App\Profile;
use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\EmulationProgramExport;
use App\Models\Categories\Titles;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use App\Slider;
use App\CourseView;

class EmulationProgramController extends Controller
{
    public function index(Request $request) {
        EmulationProgram::addGlobalScope(new CompanyScope());
        $search = $request->search;
        $fromdate = $request->start_date;
        $todate = $request->end_date;
        
        $items = $this->getItems($request);
        $set_paginate = 0;
        if($fromdate || $search || $todate) {
            $set_paginate = 1;
        } 

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($items);
            return $data;
        }
        $banners = Slider::where('location',1)->get();
        if (url_mobile()){
            $emulation_programs = EmulationProgram::where('status',1)->where('isopen',1)->paginate(10);

            return view('themes.mobile.frontend.emulation_program.index', [
                'emulation_programs' => $emulation_programs,
            ]);
        }

        return view('frontend.emulation_program',[
            'items' => $items,
            'banners' => $banners,
            'set_paginate' => $set_paginate
        ]);
    }

    public function getItems(Request $request) {
        $search = $request->search;
        $fromdate = $request->start_date;
        $todate = $request->end_date;

        $query = EmulationProgram::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }

        if ($fromdate) {
            $query->where('time_start', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where('time_end', '<=', date_convert($todate, '23:59:59'));
        }

        $query->orderByDesc('id');
        if($fromdate || $search || $todate) {
            $items = $query->get();
        } else {
            $items = $query->paginate(8);
        }

        return $items;
    }

    public function detail($emulation_id) {
        $item = EmulationProgram::find($emulation_id);
        $armorials = ArmorialEmulationProgram::where('emulation_id',$emulation_id)->get();

        $condition_courses_online = CourseView::query()
        ->select('a.*')
        ->from('el_course_view as a')
        ->join('el_emulation_program_condition AS b', function ($subquery) {
            $subquery->on('b.course_id', '=', 'a.course_id');
            $subquery->on('b.type', '=', 'a.course_type');
        })
        ->where('b.type',1)
        ->where('a.isopen',1)
        ->where('a.status',1)
        ->where('b.emulation_id',$emulation_id)
        ->paginate(5);

        $condition_courses_offline = CourseView::query()
        ->select('a.*')
        ->from('el_course_view as a')
        ->join('el_emulation_program_condition AS b', function ($subquery) {
            $subquery->on('b.course_id', '=', 'a.course_id');
            $subquery->on('b.type', '=', 'a.course_type');
        })
        ->where('b.type',2)
        ->where('a.isopen',1)
        ->where('a.status',1)
        ->where('b.emulation_id',$emulation_id)
        ->paginate(5);

        $condition_quizs = Quiz::query()
        ->select('a.*')
        ->from('el_quiz as a')
        ->join('el_emulation_program_condition as b','b.quiz_id','a.id')
        ->where('b.type',3)
        ->where('b.emulation_id',$emulation_id)
        ->paginate(5);

        $check_object_user = EmulationProgramObject::select('user_id')->where('emulation_id',$emulation_id)->get();
        $check_object = EmulationProgramObject::where('emulation_id',$emulation_id)->get();
        $get_emulation_objects_unit = EmulationProgramObject::where('emulation_id',$emulation_id)->whereNotNull('unit_id')->pluck('unit_id');
        $get_emulation_objects_title = EmulationProgramObject::where('emulation_id',$emulation_id)->whereNotNull('title_code')->pluck('title_code');
        $get_quiz_condition = EmulationProgramCondition::where('emulation_id',$emulation_id)->where('type',3)->get();
        $get_armorials = ArmorialEmulationProgram::where('emulation_id',$emulation_id)->get();

        if (!$get_quiz_condition->isEmpty()) {
            $get_sum_quizs = EmulationProgramCondition::query()
            ->selectRaw("user_id, SUM(point) as sum_point")
            ->from('el_emulation_program_condition as epc')
            ->leftjoin('el_emulation_promotion as ep', function ($join){
                $join->on('epc.quiz_id', '=', 'ep.course_id')
                    ->on('epc.type', '=', 'ep.type');
            })
            ->groupby('user_id')
            ->where('epc.emulation_id', $emulation_id)
            ->get();
        }

        $model = EmulationProgramCondition::query()
        ->selectRaw("user_id, SUM(point) as sum_point")
        ->from('el_emulation_program_condition as epc')
        ->leftjoin('el_emulation_promotion as ep', function ($join){
            $join->on('epc.course_id', '=', 'ep.course_id')
                 ->on('epc.type', '=', 'ep.type');
        })
        ->groupby('user_id')
        ->where('epc.emulation_id', $emulation_id);

        $query = Profile::query()
        ->select(['b.sum_point',
                'p.id',
                'p.user_id',
                'p.code',
                'p.email',
                'p.avatar',
                'p.firstname',
                'p.lastname',
                'p.status',
                'u.name AS unit_name',
                'c.name AS title_name',
        ])
        ->from('el_profile as p')
        ->joinSub($model,'b', function ($join){
            $join->on('b.user_id', '=', 'p.user_id');
        })
        ->leftJoin('el_unit AS u', 'u.id', '=', 'p.unit_id')
        ->leftJoin('el_titles AS c', 'c.code', '=', 'p.title_code')
        ->where('p.user_id', '>', 2);

        if (!empty($get_emulation_objects_unit) || !empty($get_emulation_objects_title)) {
            $query->where(function ($sub_query) use ($get_emulation_objects_unit, $get_emulation_objects_title) {
                $sub_query->WhereIn('u.id', $get_emulation_objects_unit);
                $sub_query->orWhereIn('c.code', $get_emulation_objects_title);
            });
        }
        $rows = $query->get();

        foreach ($rows as $row) {
            if (!$get_quiz_condition->isEmpty()) {
                foreach ($get_sum_quizs as $key => $get_sum_quiz) {
                    if ($get_sum_quiz->user_id == $row->user_id) {
                        $row->sum_point = $row->sum_point + $get_sum_quiz->sum_point;
                        foreach ($get_armorials as $key => $get_armorial) {
                            if ($row->sum_point > $get_armorial->min_score && $row->sum_point < $get_armorial->max_score) {
                                $row->armorial_images = image_file($get_armorial->images);
                                EmulationUserGetArmorial::createUserGetArmorial($emulation_id, $get_armorial->id, $row->user_id);
                            } else if($row->sum_point > $get_armorial->max_score) {
                                EmulationUserGetArmorial::deleteUserGetArmorial($emulation_id, $get_armorial->id, $row->user_id);
                            }
                        }
                    }
                }
            } else {
                foreach ($get_armorials as $key => $get_armorial) {
                    if ($row->sum_point > $get_armorial->min_score && $row->sum_point < $get_armorial->max_score) {
                        $row->armorial_images = image_file($get_armorial->images);
                        EmulationUserGetArmorial::createUserGetArmorial($emulation_id, $get_armorial->id, $row->user_id);
                    } else if($row->sum_point > $get_armorial->max_score) {
                        EmulationUserGetArmorial::deleteUserGetArmorial($emulation_id, $get_armorial->id, $row->user_id);
                    }
                }
            }
        }
        if (url_mobile()){
            return view('themes.mobile.frontend.emulation_program.detail', [
                'item' => $item,
                'armorials' => $armorials,
                'condition_courses_online' => $condition_courses_online,
                'condition_courses_offline' => $condition_courses_offline,
                'condition_quizs' => $condition_quizs,
                'check_object_user' => $check_object_user,
                'emulation_results' => $rows,
                'get_emulation_objects_unit' => $get_emulation_objects_unit,
                'check_object' => $check_object,
            ]);
        }

        return view('frontend.emulation_detail',[
            'item' => $item,
            'armorials' => $armorials,
            'condition_courses_online' => $condition_courses_online,
            'condition_courses_offline' => $condition_courses_offline,
            'condition_quizs' => $condition_quizs,
            'check_object_user' => $check_object_user,
            'emulation_results' => $rows,
            'check_object' => $check_object,
        ]);
    }

    public function loadData($items) {
        $data = '';
        foreach ($items as $item) {
            $url = route('frontend.emulation_program.detail', ['id' => $item->id]);

            $data.='<div class="col-lg-3 col-md-4 p-1">';
            $data.='    <div class="fcrse_1 my-3">';
            $data.='        <a href="'. $url .'" class="fcrse_img">';
            $data.='            <img class="picture_course" src="'. image_file($item->image) .'" alt="">';
            $data.='        </a>';
            $data.='        <div class="fcrse_content">';
            $data.='            <div class="course_names">';
            $data.='                <a href="'. $url .'" class="crse14s course_name">'. $item->name .'</a>';
            $data.='                <span class="hidden_name">'. $item->name .'</span>';
            $data.='            </div>';
            $data.='            <div class="vdtodt">';
            $data.='                <span class="vdt14"><b>Mã Chương trình:</b> '. $item->code .'</span>';
            $data.='            </div>';
            $data.='            <div class="vdtodt">';
            $data.='                <span class="vdt14"><b> '. trans('app.time') .' :</b> '. get_date($item->time_start) .' '. ($item->time_end && trans('app.to') . get_date($item->time_end) ).'</span>';
            $data.='            </div>';
            $data.='        </div>';
            $data.='    </div>';
            $data.='</div>';
        }
        return $data;
    }
}
