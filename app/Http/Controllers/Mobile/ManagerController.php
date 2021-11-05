<?php

namespace App\Http\Controllers\Mobile;

use App\Analytics;
use App\CourseBookmark;
use App\CourseRegisterView;
use App\CourseView;
use App\Feedback;
use App\LoginHistory;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Permission;
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
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Online\Entities\OnlineRegister;
use Modules\Potential\Entities\Potential;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $courses = CourseView::get();
        return view('themes.mobile.frontend.manager.index', [
            'courses' => $courses
        ]);
    }

    public function dataChartAllCourses(Request $request){
        $data = [];
        $data[] = [
            '',
            trans('app.joined'),
            trans('app.completed'),
        ];

        if (Permission::isAdmin()){
            $unit_id = Unit::pluck('id')->toArray();

            $data[] = [
                data_locale('Tập đoàn', 'Corporations'),
                $this->countRegister($unit_id),
                $this->countUserCompleted($unit_id),
            ];
        }else{
            $user_code = Profile::usercode();
            $manager = UnitManager::where('user_code', '=', $user_code)->get();

            foreach ($manager as $item){
                $unit = Unit::where('code', '=', $item->unit_code)->first();
                $unit_id = Unit::getArrayChild($unit->code);

                $unit_id = array_merge([$unit->id], $unit_id);
                $data[] = [
                    $unit->name,
                    $this->countRegister($unit_id),
                    $this->countUserCompleted($unit_id),
                ];
            }

        }

        return \response()->json($data);
    }

    public function dataChartUserNew(Request $request){
        $data = [];
        $data[] = [
            '',
            trans('app.month').' '.date('m'),
        ];

        if (Permission::isAdmin()){
            $unit_id = Unit::pluck('id')->toArray();

            $data[] = [
                data_locale('Tập đoàn', 'Corporations'),
                $this->countUserNew($unit_id),
            ];
        }else{
            $user_code = Profile::usercode();
            $manager = UnitManager::where('user_code', '=', $user_code)->get();

            foreach ($manager as $item){
                $unit = Unit::where('code', '=', $item->unit_code)->first();
                $unit_id = Unit::getArrayChild($unit->code);

                $unit_id = array_merge([$unit->id], $unit_id);
                $data[] = [
                    $unit->name,
                    $this->countUserNew($unit_id),
                ];
            }

        }

        return \response()->json($data);
    }

    public function dataChartUserByCourse(Request $request){
        $course_id = $request->course;
        $course_type = $request->type;
        $month = $request->month ? $request->month : date('m');

        $data = [];
        $data[] = [
            '',
            trans('app.joined'),
            trans('app.completed'),
        ];

        if (Permission::isAdmin()){
            $unit_id = Unit::pluck('id')->toArray();

            $data[] = [
                data_locale('Tập đoàn', 'Corporations'),
                $this->countUserByCourse($unit_id, $course_id, $course_type, $month),
                $this->countUserCompleteByCourse($unit_id, $course_id, $course_type, $month),
            ];
        }else{
            $user_code = Profile::usercode();
            $manager = UnitManager::where('user_code', '=', $user_code)->get();

            foreach ($manager as $item){
                $unit = Unit::where('code', '=', $item->unit_code)->first();
                $unit_id = Unit::getArrayChild($unit->code);

                $unit_id = array_merge([$unit->id], $unit_id);
                $data[] = [
                    $unit->name,
                    $this->countUserByCourse($unit_id, $course_id, $course_type, $month),
                    $this->countUserCompleteByCourse($unit_id, $course_id, $course_type, $month),
                ];
            }

        }

        return \response()->json($data);
    }

    public function countRegister($unit_id){

        $query = CourseRegisterView::whereIn('unit_id', $unit_id)->where('status_level_2', '=', 1);

        return $query->count();
    }

    public function countUserCompleted($unit_id){
        $prefix = \DB::getTablePrefix();
        $query = \DB::table('el_course_complete as a')
            ->select(['a.*'])
            ->leftJoin('el_profile as b','b.user_id', '=','a.user_id')
            ->leftJoin('el_unit as c', 'c.code', '=', 'b.unit_code')
            ->whereIn('c.id', $unit_id);

        return $query->count();
    }

    public function countUserNew($unit_id){
        $query = Profile::query()
            ->select(['a.*'])
            ->from('el_profile as a')
            ->leftJoin('el_unit as b', 'b.code', '=', 'a.unit_code')
            ->whereIn('b.id', $unit_id)
            ->where(function ($sub){
                $sub->orWhere(\DB::raw('month('.\DB::getTablePrefix().'a.join_company)'), '=', date('m'));
                $sub->orWhere(\DB::raw('month('.\DB::getTablePrefix().'a.created_at)'), '=', date('m'));
            });

        return $query->count();
    }

    public function countUserByCourse($unit_id, $course_id, $course_type, $month)
    {
        if ($course_type == 1){
            $query = OnlineRegister::query()
                ->select('a.*')
                ->from('el_online_register as a')
                ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id')
                ->leftJoin('el_unit as c', 'c.code', '=', 'b.unit_code')
                ->where('a.status', '=', 1)
                ->where('a.course_id', '=', $course_id)
                ->whereIn('c.id', $unit_id)
                ->where(\DB::raw('month('.\DB::getTablePrefix().'a.created_at)'), '=', $month);

            return $query->count();
        }else{
            $query = OfflineRegister::query()
                ->select('a.*')
                ->from('el_offline_register as a')
                ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id')
                ->leftJoin('el_unit as c', 'c.code', '=', 'b.unit_code')
                ->where('a.status', '=', 1)
                ->where('a.course_id', '=', $course_id)
                ->whereIn('c.id', $unit_id)
                ->where(\DB::raw('month('.\DB::getTablePrefix().'a.created_at)'), '=', $month);

            return $query->count();
        }
    }

    public function countUserCompleteByCourse($unit_id, $course_id, $course_type, $month)
    {
        if ($course_type == 1){
            $query = OnlineCourseComplete::query()
                ->select('a.*')
                ->from('el_online_course_complete as a')
                ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id')
                ->leftJoin('el_unit as c', 'c.code', '=', 'b.unit_code')
                ->where('a.course_id', '=', $course_id)
                ->whereIn('c.id', $unit_id)
                ->where(function ($sub) use ($month){
                    $sub->orWhere(\DB::raw('month('.\DB::getTablePrefix().'a.created_at)'), '=', $month);
                    $sub->orWhere(\DB::raw('month('.\DB::getTablePrefix().'a.created_at)'), '<', $month);
                });

            return $query->count();
        }else{
            $query = OfflineCourseComplete::query()
                ->select('a.*')
                ->from('el_offline_course_complete as a')
                ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id')
                ->leftJoin('el_unit as c', 'c.code', '=', 'b.unit_code')
                ->where('a.course_id', '=', $course_id)
                ->whereIn('c.id', $unit_id)
                ->where(function ($sub) use ($month){
                    $sub->orWhere(\DB::raw('month('.\DB::getTablePrefix().'a.created_at)'), '=', $month);
                    $sub->orWhere(\DB::raw('month('.\DB::getTablePrefix().'a.created_at)'), '<', $month);
                });

            return $query->count();
        }
    }
}
