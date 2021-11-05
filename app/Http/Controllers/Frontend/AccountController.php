<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Subject;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\NewRecruitment\Entities\NewRecruitmentRoadmap;
use Modules\NewRecruitment\Entities\NewRecruitment;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Profile::where('user_id', Auth::id())->first();
        $title = Titles::where('code','=',$user->title_code)->first();
        return view('frontend.account',[
            'user' => $user,
            'title' => $title,
        ]);
    }

    public function getDataTrainingRoadmap()
    {
        $isnewrecruitment = NewRecruitment::where('user_id', '=', Auth::id())
            ->where('end_date', '>', date('Y-m-d H:i:s'))->first();

        if ($isnewrecruitment) {
            $this->getDataTrainingRoadmapNewRecruitment();
        }

        $this->getDataTrainingRoadmapBasic();
    }

    public function getDataTrainingRoadmapBasic() {
        $user = Profile::where('user_id', Auth::id())->first();
        $title = Titles::where('code','=',$user->title_code)->first();
        $query = TrainingRoadmap::query();
        $query->select(['a.*' , 'b.code AS subject_code','b.name AS subject_name', 'c.code AS title_code','c.name AS title_name' ]);
        $query->from('el_trainingroadmap AS a');
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
        $query->leftJoin('el_titles AS c', 'c.id', '=', 'a.title_id');
        $query->where('a.title_id','=',$title->id);
        $count = $query->count();   

        $rows = $query->get();
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataTrainingRoadmapNewRecruitment() {
        $user = Profile::where('user_id', Auth::id())->first();
        $title = Titles::where('code','=',$user->title_code)->first();
        $query = NewRecruitmentRoadmap::query();
        $query->select(['a.*' , 'b.code AS subject_code','b.name AS subject_name', 'c.code AS title_code','c.name AS title_name' ]);
        $query->from('el_new_recruitment_roadmap AS a');
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
        $query->leftJoin('el_titles AS c', 'c.id', '=', 'a.title_id');
        $query->where('a.title_id','=',$title->id);

        $count = $query->count();   

        $rows = $query->get();
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataTrainingRoadmapOther(Request $request)
    {
        $titlesearch = $request->input('titlesearch');
        $query = TrainingRoadmap::query();
        $query->select(['a.*' , 'b.code AS subject_code','b.name AS subject_name', 'c.code AS title_code','c.name AS title_name' ]);
        $query->from('el_trainingroadmap AS a');
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
        $query->leftJoin('el_titles AS c', 'c.id', '=', 'a.title_id');
        
        if($titlesearch){
            $query->where('c.id' ,'=' , $titlesearch );
        };
        $count = $query->count();   
        $rows = $query->get();
        json_result(['total' => $count, 'rows' => $rows]);
    }
}