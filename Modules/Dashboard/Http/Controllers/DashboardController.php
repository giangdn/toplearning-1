<?php

namespace Modules\Dashboard\Http\Controllers;

use App\CourseResultStatistic;
use App\CourseStatistic;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\OnlineCourseStatistic;
use App\NewsStatistic;
use App\LibrariesStatistic;
use App\ForumsStatistic;
use App\Profile;
use App\ProfileView;
use App\Scopes\DraftScope;
use App\VisitsStatistic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jenssegers\Agent\Agent;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizStatistic;
use Modules\User\Entities\User;
use Shetabit\Visitor\Visitor;
use Shetabit\Visitor\Traits\Visitable;
//use  Analytics;
use Spatie\Analytics\Period;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Modules\Libraries\Entities\Libraries;
use Modules\News\Entities\News;
use Modules\Forum\Entities\Forum;
use Modules\Forum\Entities\ForumComment;
use Modules\Forum\Entities\ForumThread;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function __construct()
    {
        $this->middleware(['permission:dashboard']);
        //,'permission:dashboard'
    }

    public function index(Request $request)
    {
        OnlineCourse::addGlobalScope(new DraftScope());
        OfflineCourse::addGlobalScope(new DraftScope());
        ProfileView::addGlobalScope(new DraftScope('user_id'));
        Quiz::addGlobalScope(new DraftScope());

        $total_online_course = $this->countOnlineByCourse();
        $total_offline_course = $this->countOfflineByCourse();
        $total_users = ProfileView::where('user_id', '>', 2)->where('type_user', '=', 1)->where('status_id', '!=', 0)->count();
        $total_quiz = Quiz::countQuiz();

        /*****thống kê khóa học theo năm*********/
        $static_online_course_y = CourseStatistic::where('year','=',date('Y'))->where('course_type','=',1)->first(['t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12']);
        $static_offline_course_y = CourseStatistic::where('year','=',date('Y'))->where('course_type','=',2)->first(['t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12']);
        $static_online_course_y= $static_online_course_y ? $static_online_course_y->toArray() :[];
        $static_offline_course_y = $static_offline_course_y ? $static_offline_course_y->toArray():[];

        // Thống kê truy cập khóa học online
        $data_visit_statistic_online = OnlineCourseStatistic::where('year','=',date('Y'))->first(['t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12']);
        $data_visit_statistic_online = $data_visit_statistic_online ? $data_visit_statistic_online->toArray() :[];

        // Thống kê truy cập tin tức, video ,hình ảnh
        $data_visit_statistic_news = NewsStatistic::where('year','=',date('Y'))->where('type','=',0)->first(['t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12']);
        $data_visit_statistic_news = $data_visit_statistic_news ? $data_visit_statistic_news->toArray() :[];

        // Thống kê số lượng tin tức, video ,hình ảnh
        $count_video_new = News::where('type',2)->count();
        $count_post_new = News::where('type',1)->count();
        $count_image_new = News::where('type',3)->count();

        // Thống kê truy cập tài liệu, ebook, sách giấy, audio, video
        $data_visit_statistic_libraries = LibrariesStatistic::where('year','=',date('Y'))->where('type','=',0)->first(['t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12']);
        $data_visit_statistic_libraries = $data_visit_statistic_libraries ? $data_visit_statistic_libraries->toArray() :[];

        // Thống kê số lượng tài liệu, ebook, sách giấy, audio, video
        $count_video_libraries = Libraries::where('type',4)->count();
        $count_audio_libraries = Libraries::where('type',5)->count();
        $count_book_libraries = Libraries::where('type',1)->count();
        $count_document_libraries = Libraries::where('type',3)->count();
        $count_ebook_libraries = Libraries::where('type',2)->count();

        // Thống kê truy cập diễn đàn
        $data_visit_statistic_forums = ForumsStatistic::where('year','=',date('Y'))->first(['t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12']);
        $data_visit_statistic_forums = $data_visit_statistic_forums ? $data_visit_statistic_forums->toArray() :[];

        // Thống kê số lượng chủ đề, bài viết, bình luận điễn đàn
        $count_forum = Forum::count();
        $count_forum_comment = ForumComment::count();
        $count_forum_post = ForumThread::count();

        /*** Thống kê trạng thái khóa học***********/
        $course_statistic = \DB::query()->select('*')->from('el_course_statistic_general')->first();

        /******* khóa học online lastest ***********/
        $lastest_online_course = OnlineCourse::getLastestCourse();
        $lastest_offline_course = OfflineCourse::getLastestCourse();

        /**********kỳ thi lastest*************/
        $statistic_quiz = QuizStatistic::where('year','=',date('Y'))->first(['t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12']);
        $statistic_quiz = $statistic_quiz?$statistic_quiz->toArray():[];
        $lastest_quiz = Quiz::getLastestQuiz();

        /**********hoàn thành/chưa hoàn thành**********/
        $course_result_finish_statistic = CourseResultStatistic::where('year','=',date('Y'))->where('result','=',1)
            ->selectRaw('sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,sum(t12) as t12')->first();
        $course_result_finish_statistic = $course_result_finish_statistic ? $course_result_finish_statistic->toArray() : [];

        $course_result_fail_statistic = CourseResultStatistic::where('year','=',date('Y'))->where('result','=',0)
            ->selectRaw('sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,sum(t12) as t12')->first();
        $course_result_fail_statistic = $course_result_fail_statistic ? $course_result_fail_statistic->toArray() : [];

        $rate_finish = $course_result_finish_statistic['t1'] + $course_result_finish_statistic['t2'] + $course_result_finish_statistic['t3'] + $course_result_finish_statistic['t4']
                + $course_result_finish_statistic['t5'] + $course_result_finish_statistic['t6'] + $course_result_finish_statistic['t7'] + $course_result_finish_statistic['t8']
                + $course_result_finish_statistic['t9'] + $course_result_finish_statistic['t10'] + $course_result_finish_statistic['t11'] + $course_result_finish_statistic['t12'];

        $rate_fail = $course_result_fail_statistic['t1'] + $course_result_fail_statistic['t2'] + $course_result_fail_statistic['t3'] + $course_result_fail_statistic['t4']
            + $course_result_fail_statistic['t5'] + $course_result_fail_statistic['t6'] + $course_result_fail_statistic['t7'] + $course_result_fail_statistic['t8']
            + $course_result_fail_statistic['t9'] + $course_result_fail_statistic['t10'] + $course_result_fail_statistic['t11'] + $course_result_fail_statistic['t12'];

        /*********thống kê trình duyệt**********/
        $browser_statistic = $this->browser_statistic();
        $device_category = $this->device_category();

        /**********thống kê truy cập năm**********/
        $visit_statistic = $this->visit_statistic();

        return view('dashboard::index',[
            'total_online_course'               => $total_online_course,
            'total_offline_course'              => $total_offline_course,
            'total_users'                       => $total_users,
            'total_quiz'                        => $total_quiz,
            'static_online_course_y'            => array_values($static_online_course_y),
            'static_offline_course_y'           => array_values($static_offline_course_y),
            'users_online'                      => \App\User::countUsersOnline(),
            'course_statistic'                  => $course_statistic,
            'lastest_online_course'             => $lastest_online_course,
            'lastest_offline_course'            => $lastest_offline_course,
            'statistic_quiz'                    => array_values($statistic_quiz),
            'lastest_quiz'                      => $lastest_quiz,
            'course_result_finish_statistic'    => array_values($course_result_finish_statistic),
            'course_result_fail_statistic'      => array_values($course_result_fail_statistic),
            'rate_finish'                       => $rate_finish,
            'rate_fail'                         => $rate_fail,
            'browser_statistic'                 => $browser_statistic,
            'device_category'                   => $device_category,
            'visit_statistic'                   => $visit_statistic,
            'data_visit_statistic_online'       => array_values($data_visit_statistic_online),
            'data_visit_statistic_news'         => array_values($data_visit_statistic_news),
            'data_visit_statistic_libraries'    => array_values($data_visit_statistic_libraries),
            'data_visit_statistic_forums'       => array_values($data_visit_statistic_forums),
            'count_video_libraries'             => $count_video_libraries,
            'count_audio_libraries'             => $count_audio_libraries,
            'count_ebook_libraries'             => $count_ebook_libraries,
            'count_book_libraries'              => $count_book_libraries,
            'count_document_libraries'          => $count_document_libraries,
            'count_video_new'                   => $count_video_new,
            'count_image_new'                   => $count_image_new,
            'count_post_new'                    => $count_post_new,
            'count_forum'                       => $count_forum,
            'count_forum_comment'               => $count_forum_comment,
            'count_forum_post'                  => $count_forum_post,
        ]);

    }
    private function visit_statistic(){
        $year = date('Y');
        $visits = VisitsStatistic::where('year','=',$year)->where('type','=','M')->get(['name','value']);//->pluck('value');
        $mer =[]; $arr =[];
        foreach ($visits as $key=>$value){
            $mer[$value->name] =$value->value;
        }
        for ($i=1;$i<=12;$i++){
            if (!isset($mer[$i]))
                $arr[$i-1] = 0;
            else
                $arr[$i-1] = $mer[$i];
        }
        return $arr ;

        /*$startDate = Carbon::createFromFormat('Y-m-d', '2020-01-01');
        $endDate = Carbon::createFromFormat('Y-m-d','2020-08-06');
        $period = Period::create($startDate, $endDate);
        $visits = Analytics::performQuery($period,'ga:users',['dimensions'=>'ga:month'])['rows'];
        return collect($visits)->pluck('1');
        foreach ($visits as $key=>$value){
            $arr[] = [$value[0]=>$value[1]];
        }
        return $arr;*/
    }
    private  function browser_statistic(){
        $startDate = Carbon::createFromFormat('Y-m-d', '2020-01-01');
        $endDate = Carbon::createFromFormat('Y-m-d','2020-08-06');
        $period = Period::create($startDate, $endDate);
//        $browser = Analytics::performQuery($period,'ga:users',['dimensions'=>'ga:browser','filters'=>'ga:browser==Chrome,ga:browser==Firefox,ga:browser==Internet Explorer,ga:browser==Coc Coc,ga:browser==Edge,ga:browser==Safari,ga:browser==Opera'])['rows'];
        $browser_data = VisitsStatistic::where('type','=','browser')->get(['name','value']);
        $browser =[];
        foreach ($browser_data as $item) {
            $browser[] = [$item->name,$item->value];
        }
        $text_color = ['text-red', 'text-green', 'text-yellow', 'text-aqua', 'text-info', 'text-grey', 'text-gray', 'text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-dark', 'text-muted'];
        foreach($browser as $key => $item){
            array_push($item, $text_color[$key]);
            $browser[$key] = $item;
        }
        return $browser;
    }
    private function device_category(){
        $startDate = Carbon::createFromFormat('Y-m-d', '2020-01-01');
        $endDate = Carbon::createFromFormat('Y-m-d','2020-08-06');
        $period = Period::create($startDate, $endDate);
//        $device = Analytics::performQuery($period,'ga:users',['dimensions'=>'ga:deviceCategory'])['rows'];
        $device_data = VisitsStatistic::where('type','=','device')->get(['name','value']);
        $device = [];
        foreach ($device_data as $item){
            $device[] = [$item->name,$item->value];
        }
        return  ($device);
    }

    private function countOnlineByCourse(){
        $count_online_by_course = 0;
        for ($i = 1; $i <= 12; $i++) {
            $i = ($i < 10) ? '0' . $i : $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $query = OnlineCourse::query();
            $query->select(['el_online_course.id']);
            $query->where('el_online_course.status', '=', 1);
            $query->where('el_online_course.isopen', '=', 1);
            $query->where('el_online_course.start_date','<=', $last_month)
                ->where(function ($sub) use ($first_month, $last_month){
                    $sub->where('el_online_course.end_date','>=', $last_month);
                    $sub->orwhere('el_online_course.end_date', '>=', $first_month);
                    $sub->orWhereNull('el_online_course.end_date');
                });

            $query->groupBy(['el_online_course.id']);
            $online_by_course = $query->get();
            $count_online_by_course += $online_by_course->count();
        }

        return $count_online_by_course;
    }

    private function countOfflineByCourse(){
        $count_offline_by_course = 0;
        for ($i = 1; $i <= 12; $i++) {
            $i = ($i < 10) ? '0' . $i : $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $query = OfflineCourse::query();
            $query->select(['el_offline_course.id']);
            $query->where('el_offline_course.status', '=', 1);
            $query->where('el_offline_course.isopen', '=', 1);
            $query->where('el_offline_course.start_date','<=', $last_month)
                ->where(function ($sub) use ($first_month, $last_month){
                    $sub->where('el_offline_course.end_date','>=', $last_month);
                    $sub->orwhere('el_offline_course.end_date', '>=', $first_month);
                    $sub->orWhereNull('el_offline_course.end_date');
                });

            $query->groupBy(['el_offline_course.id']);
            $offline_by_course = $query->get();
            $count_offline_by_course += $offline_by_course->count();
        }
        return $count_offline_by_course;
    }
}
