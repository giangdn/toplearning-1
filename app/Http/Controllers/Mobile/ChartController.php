<?php

namespace App\Http\Controllers\Mobile;

use App\Analytics;
use App\CourseBookmark;
use App\CourseRegisterView;
use App\Feedback;
use App\LoginHistory;
use App\Models\Categories\Titles;
use App\Profile;
use App\Slider;
use App\SpeedText;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ConvertTitles\Entities\ConvertTitles;
use Modules\Forum\Entities\ForumThread;
use Modules\Libraries\Entities\Libraries;
use Modules\News\Entities\News;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineRegister;
use Modules\Potential\Entities\Potential;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;

class ChartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chart(){
        $lay = 'chart';
        $count_user_login = Analytics::where('user_id', '=', \Auth::id())
            ->where(\DB::raw('year(day)'), '=', date('Y'))->count();

        $laster_news = News::getLasterNews();
        $count_user_register_online  = OnlineCourse::countUserRegisterOnline(Auth::id());

        return view('themes.mobile.frontend.chart', [
            'count_user_login' => $count_user_login,
            'lay' => $lay,
            'training_roadmap_course' => $this->getCourseTrainingRoadmap(),
            'laster_news' => $laster_news,
            'count_user_register_online' => $count_user_register_online,
            'user_max_point' => $this->getUserMaxPoint(),
            'teacher_max_point' => $this->getTeacherMaxPoint(),
        ]);
    }

    public function dataChart(Request $request){
        $type = $request->type;

        $data = [];
        if (empty($type)){
            $data[] = [
                'Month',
                @trans('app.online_course'),
                @trans("app.in_house"),
            ];
        }else{
            $data[] = [
                'Month',
                @trans('app.online_course'),
            ];
        }

        for ($i = 1; $i <= 12; $i++){
            $online = $this->getOnlineRegister(Auth::id(), $i);
            if(empty($type)){
                $offline = $this->getOfflineRegister(Auth::id(), $i);
                $data[] = [
                    ($i%2 != 0) ? 'T'.$i : '',
                    $online,
                    $offline,
                ];
            }else{
                $data[] = [
                    ($i%2 != 0) ? 'T'.$i : '',
                    $online,
                ];
            }
        }

        return \response()->json($data);
    }

    public function trainingRoadmapCourse()
    {
        return view('themes.mobile.frontend.training_roadmap.course', [
            'training_roadmap_course' => $this->getCourseTrainingRoadmap(),
        ]);
    }

    public function getOnlineRegister($user_id, $month){
        $register = OnlineRegister::query()
            ->from('el_online_register as a')
            ->leftJoin('el_online_course as b', 'b.id', '=', 'a.course_id')
            ->where('a.user_id', '=', $user_id)
            ->where(\DB::raw('month(start_date)'),'=', $month)
            ->where(\DB::raw('year(start_date)'),'=', date('Y'))
            ->where('a.status', '=', 1)
            ->count();

        return $register;
    }

    public function getOfflineRegister($user_id, $month){
        $register = OfflineRegister::query()
            ->from('el_offline_register as a')
            ->leftJoin('el_offline_course as b', 'b.id', '=', 'a.course_id')
            ->where('a.user_id', '=', $user_id)
            ->where(\DB::raw('month(start_date)'),'=', $month)
            ->where(\DB::raw('year(start_date)'),'=', date('Y'))
            ->where('a.status', '=', 1)
            ->count();

        return $register;
    }

    /*Lấy điểm cao nhất của học viên*/
    public function getUserMaxPoint(){
        $user = Profile::query()
            ->select(['profile.user_id', 'user_point.point', 'level.name', 'level.images'])
            ->from('el_profile as profile')
            ->leftJoin('el_promotion_user_point as user_point', 'user_point.user_id', '=', 'profile.user_id')
            ->leftJoin('el_promotion_level as level', 'level.level', '=', 'user_point.level_id')
            ->where('profile.user_id', '=', Auth::id())
            ->first();

        return $user;
    }

    /*Lấy giảng viên có điểm cao nhất*/
    public function getTeacherMaxPoint(){
        $max_point = PromotionUserPoint::getMaxPoint();

        $user = Profile::query()
            ->select(['profile.user_id', 'user_point.point'])
            ->from('el_profile as profile')
            ->leftJoin('el_promotion_user_point as user_point', 'user_point.user_id', '=', 'profile.user_id')
            ->where('user_point.point', '=', $max_point)
            ->where('profile.status', '=', 1)
            ->whereIn('profile.user_id', function ($sub){
                $sub->select(['user_id'])
                    ->from('el_training_teacher')
                    ->pluck('user_id')->toArray();
            })->first();

        return $user;
    }

    /*khóa học theo lộ trình*/
    public function getCourseTrainingRoadmap()
    {
        $user_convert_titles = ConvertTitles::query()
            ->where('user_id','=',\Auth::id())
            ->where('end_date','>',date('Y-m-d H:i:s'))
            ->first();

        $user_potential = Potential::query()
            ->where('user_id','=',\Auth::id())
            ->where('end_date','>',date('Y-m-d H:i:s'))
            ->first();

        if ($user_convert_titles){
            $roadmap = 'el_convert_titles_roadmap';
            $title = Titles::find($user_convert_titles->title_id);
        }
        elseif ($user_potential){
            $roadmap = 'el_potential_roadmap';
            $user = Profile::find(\Auth::id());
            $title = Titles::where('code','=', $user->title_code)->first();
        }
        else{
            $roadmap = 'el_trainingroadmap';
            $user = Profile::find(\Auth::id());
            $title = Titles::where('code','=', $user->title_code)->first();
        }

        $subQuery = \DB::table('el_course_register_view as a1')
            ->join('el_course_view as a2', function ($join){
                $join->on('a1.course_id','=','a2.course_id');
                $join->on('a1.course_type','=','a2.course_type');
            })
            ->where('a1.user_id','=',\Auth::id())
            ->groupBy(['a2.subject_id','a2.course_type'])
            ->select([
                \DB::raw('MAX('.\DB::getTablePrefix().'a2.course_id) as course_id'),
                'a2.subject_id',
                'a2.course_type'
            ]);

        $query = \DB::query();
        $query->select([
            'c.*'
        ]);
        $query->from("$roadmap AS a");
        $query->joinSub($subQuery,'b', function ($join){
            $join->on('b.course_type', '=', 'a.training_form');
            $join->on('b.subject_id', '=', 'a.subject_id');
        });
        $query->join('el_course_view AS c', function ($join){
            $join->on('c.course_id', '=', 'b.course_id');
            $join->on('c.course_type', '=', 'b.course_type');
        });
        $query->join('el_course_register_view as d',function ($join){
            $join->on('d.course_id', '=', 'c.id');
            $join->on('d.course_type', '=', 'c.course_type')->where('d.user_id', '=', \Auth::id());
        });
        $query->where('c.status','=', 1);
        $query->where('c.isopen','=', 1);
        $query->where('a.title_id','=', @$title->id);

        return $query->paginate(12);
    }
}
