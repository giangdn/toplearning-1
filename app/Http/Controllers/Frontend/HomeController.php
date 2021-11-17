<?php

namespace App\Http\Controllers\Frontend;

use App\CourseBookmark;
use App\Feedback;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Notifications;
use App\Permission;
use App\Profile;
use App\ProfileView;
use App\Scopes\CompanyScope;
use App\Slider;
use App\SpeedText;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ConvertTitles\Entities\ConvertTitles;
use Modules\Forum\Entities\ForumComment;
use Modules\Forum\Entities\ForumThread;
use Modules\Libraries\Entities\Libraries;
use Modules\News\Entities\News;
use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifySend;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Potential\Entities\Potential;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\User\Entities\TrainingProcess;
use App\EmulationProgram;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyObject;
use App\CourseView;
use App\CourseRegisterView;

class HomeController extends Controller
{
    public function changeLanguage($lang)
    {
        if (url_mobile()) {
            \App::setLocale($lang);
            session()->put('locale', $lang);
            return redirect()->back();
        }

        \App::setLocale($lang);
        session()->put('locale', $lang);
        return redirect()->back();
    }

    public function index()
    {
        $user_id = getUserId();
        $user_type = getUserType();

        $profile = ProfileView::find(Auth::id());
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();
        $laster_news = News::getLasterNews();
        $count_offline_register = OfflineRegister::whereUserId(Auth::id())->whereStatus(1)->count();
        $count_online_register = OnlineRegister::whereUserId(Auth::id())->whereStatus(1)->count();

        $count_quiz = Quiz::query()
            ->from('el_quiz AS a')
            ->join('el_quiz_part AS b', function ($subquery) use ($user_id, $user_type) {
                $subquery->on('b.quiz_id', '=', 'a.id')
                    ->whereIn('b.id', function ($subquery2) use ($user_id, $user_type) {
                        $subquery2->select(['part_id'])
                            ->from('el_quiz_register')
                            ->where('user_id', '=', $user_id)
                            ->where('type', '=', $user_type)
                            ->whereColumn('quiz_id', '=', 'a.id');
                    });
            })
            ->where('a.status', '=', 1)
            ->where('a.is_open', '=', 1)
            ->where(function ($sub) {
                $sub->orWhere('a.quiz_type', '=', 3);
                $sub->orWhereIn('a.id', function ($subquery) {
                    $subquery->select(['quiz_id'])
                        ->from('el_offline_course')
                        ->whereNotNull('quiz_id')
                        ->where('status', '=', 1)
                        ->where('isopen', '=', 1);
                });
            })
            ->whereIn('a.id', function ($subquery) use ($user_id, $user_type) {
                $subquery->select(['quiz_id'])
                    ->from('el_quiz_register')
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', $user_type);
            })
            ->count();

        if (url_mobile()) {
            $lay = 'home';
            $laster_thread = ForumThread::getLasterThread();
            $unit_arr = Unit::getTreeParentUnit($profile->unit_code);

            Slider::addGlobalScope(new CompanyScope());
            $sliders = Slider::where('status', '=', 1)
                ->where('type', '=', 2)
                ->where('location', '!=', 1)
                ->where(function ($sub) use ($unit_arr) {
                    $sub->where('location', '=', 0);
                    foreach ($unit_arr as $item) {
                        $sub->orWhereIn('location', [$item->id]);
                    }
                })->get();

            Survey::addGlobalScope(new CompanyScope());
            $model = Survey::query();
            $model->where('status', '=', 1);
            $model->where('end_date', '>', date('Y-m-d H:i:s'));
            $model->where(function ($subquery) use ($profile, $unit, $title) {
                $subquery->orWhereIn('id', function ($subquery2) use ($profile, $unit, $title) {
                    $subquery2->select(['survey_id'])
                        ->from('el_survey_object')
                        ->where('user_id', '=', $profile->user_id)
                        ->orWhere('title_id', '=', @$title->id)
                        ->orWhere('unit_id', '=', @$unit->id);
                });
            });
            $count_survey = $model->count();

            $new_onlines = OnlineCourse::getNewCourse();
            $feedbacks = Feedback::get();

            EmulationProgram::addGlobalScope(new CompanyScope());
            $emulation_programs = EmulationProgram::where('status', 1)->where('isopen', 1)->get();
            $count_my_course = ($count_offline_register + $count_online_register);

            return view('themes.mobile.frontend.home', [
                'total_learners' => /*$this->totalLearners()*/ '',
                'laster_news' => $laster_news,
                'emulation_programs' => $emulation_programs,
                'count_my_course' => $count_my_course,
                'count_quiz' => $count_quiz,
                'count_survey' => $count_survey,
                'new_onlines' => $new_onlines,
                'laster_thread' => $laster_thread,
                'feedbacks' => $feedbacks,
                'count_online' => /*$this->countOnline()*/ '',
                'count_offline' => /*$this->countOffline()*/ '',
                'count_ebook' => /*$this->countEBook()*/ '',
                'course_beling_held' => /*$this->countCourseBeingHeld()*/ '',
                'lay' => $lay,
                'user_max_point' => $this->getUserMaxPoint(),
                'teacher_max_point' => $this->getTeacherMaxPoint(),
                'sliders' => $sliders,
            ]);
        } else {
            $count_register_course_by_user = ($count_offline_register + $count_online_register);
            $count_complete_course_by_user = DB::table('el_course_complete')->where('user_id', '=', Auth::id())->count();

            $get_register_online = CourseRegisterView::where('course_type', 1)->where('user_id', Auth::id())->where('status', 1)->pluck('course_id')->toArray();
            $model_online = CourseView::query();
            $model_online->select(['a.course_id', 'a.start_date', 'a.end_date', 'a.register_deadline', 'a.name', 'a.code']);
            $model_online->from('el_course_view as a');
            $model_online->where('a.status', 1);
            $model_online->where('a.course_type', 1);
            $model_online->where('a.isopen', 1);
            $model_online->whereIn('a.course_id', $get_register_online);
            $countMyOnlineCourse = $model_online->get();
            $my_onlCourse = $model_online->take(5)->get();

            $get_register_offline = CourseRegisterView::where('course_type', 2)->where('user_id', Auth::id())->where('status', 1)->pluck('course_id')->toArray();
            $model_offline = CourseView::query();
            $model_offline->select(['a.*']);
            $model_offline->from('el_course_view as a');
            $model_offline->whereIn('a.course_id', $get_register_offline);
            $model_offline->where('a.status', 1);
            $model_offline->where('a.course_type', 2);
            $model_offline->where('a.isopen', 1);
            $countMyOfflineCourse = $model_offline->get();
            $my_offCourse = $model_offline->take(5)->get();

            $my_quiz = QuizRegister::where('user_id', \auth()->id())->count();
            $userPoint = PromotionUserPoint::where('user_id', \auth()->id());
            $point = $userPoint->exists() ? $userPoint->first()->point : 0;
            $chart = $this->getRegisterCourse();
            $notify = NotifySend::getNotifyNew(5);
            $count_subject_by_level_subject = function ($level_subject_id, $complete = null) {
                $profile = Profile::find(Auth::id());
                $subQuery = \DB::table('el_training_process')
                    ->where('user_id', '=', $profile->user_id)
                    ->where('titles_code', '=', $profile->title_code)
                    ->groupBy('subject_id')
                    ->select([
                        \DB::raw('MAX(id) as id'),
                        'subject_id',
                    ]);

                $query = \DB::query();
                $query->from("el_trainingroadmap AS a");
                $query->joinSub($subQuery, 'b', function ($join) {
                    $join->on('b.subject_id', '=', 'a.subject_id');
                });
                $query->leftJoin('el_subject as d', function ($join) {
                    $join->on('d.id', '=', 'b.subject_id');
                });
                $query->leftJoin('el_training_process as c', function ($join) {
                    $join->on('c.id', '=', 'b.id');
                });
                $query->where('d.level_subject_id', '=', $level_subject_id);
                $query->where('a.title_id', '=', @$profile->titles->id);
                if ($complete) {
                    $query->where('c.pass', '=', 1);
                }

                return $query->count();
            };

            return view('frontend.home', [
                'count_quiz' => $count_quiz,
                'countMyOnlineCourse' => $countMyOnlineCourse,
                'countMyOfflineCourse' => $countMyOfflineCourse,
                'my_onl' => $my_onlCourse,
                'my_off' => $my_offCourse,
                'point' => $point,
                'chart' => $chart,
                'notify' => $notify,
                'training_roadmap_course' => $this->getCourseTrainingRoadmap(),
                'laster_news' => $laster_news,
                'get_course_new' => $this->getFiveCourseNew(),
                'chartCourseByUser' => $this->chartCourseByUser(),
                'count_register_course_by_user' => $count_register_course_by_user,
                'count_complete_course_by_user' => $count_complete_course_by_user,
                'chartSubjectByUser' => $this->chartSubjectByUser(),
                'count_register_subject_by_user' => $this->chartSubjectByUser()[0] + $this->chartSubjectByUser()[1],
                'count_complete_subject_by_user' => $this->chartSubjectByUser()[1],
                'getLevelSubjectByUser' => $this->getLevelSubjectByUser(),
                'count_subject_by_level_subject' => $count_subject_by_level_subject
            ]);
        }
    }

    public function countOnline()
    {
        $query = OnlineCourse::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        return $query->count();
    }

    public function countOffline()
    {
        $query = OfflineCourse::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        return $query->count();
    }

    public function countBook()
    {
        $query = Libraries::query();
        $query->where('type', '=', 1);
        $query->where('status', '=', 1);
        return $query->count();
    }

    public function countEBook()
    {
        $query = Libraries::query();
        $query->where('type', '=', 2);
        $query->where('status', '=', 1);
        return $query->count();
    }

    public function countDocument()
    {
        $query = Libraries::query();
        $query->where('type', '=', 3);
        $query->where('status', '=', 1);
        return $query->count();
    }

    public function countQuiz()
    {
        $query = Quiz::query();
        $query->where('status', '=', 1);
        return $query->count();
    }

    public function saveCourseBookmark($course_id, $course_type, $my_course)
    {
        $bookmark = CourseBookmark::where('course_id', $course_id)->where('type', $course_type)->where('user_id', \auth()->id());
        if (!$bookmark->exists()) {
            $model = new CourseBookmark();
            $model->course_id = $course_id;
            $model->type = $course_type;
            $model->user_id = Auth::id();
            $model->save();
        }
        if ($course_type == 1 && $my_course == 0) {
            // return redirect()->route('module.online');
            return redirect()->route('frontend.all_course');
        } else if ($my_course == 1) {
            return redirect()->route('module.frontend.user.my_course', ['type' => 0]);
        }
        // return redirect()->route('module.offline');
        return redirect()->route('frontend.all_course');
    }

    public function removeCourseBookmark($course_id, $course_type, $my_course)
    {
        CourseBookmark::query()
            ->where('course_id', '=', $course_id)
            ->where('type', '=', $course_type)
            ->where('user_id', '=', Auth::id())
            ->delete();

        if ($course_type == 1 && $my_course == 0) {
            return redirect()->route('frontend.all_course');
        } else if ($my_course == 1) {
            return redirect()->route('module.frontend.user.my_course', ['type' => 0]);
        }
        return redirect()->route('frontend.all_course');
    }

    /*Tổng số user*/
    public function totalLearners()
    {
        $count = Profile::where('status', '!=', 0)->where('user_id', '>', 2)->count();
        return $count;
    }

    /*Đếm số Khóa học theo tháng*/
    public function getCourseNew()
    {
        $online = OnlineCourse::where('status', '=', 1)
            ->where(\DB::raw('month(start_date)'), '=', date('m'))->count();

        $offline = OfflineCourse::where('status', '=', 1)
            ->where(\DB::raw('month(start_date)'), '=', date('m'))->count();

        return ($online + $offline);
    }

    /*khóa học đang tổ chức*/
    public function countCourseBeingHeld()
    {
        $now = date('Y-m-d H:i:s');
        $online = OnlineCourse::where('status', '=', 1)
            ->where('start_date', '<=', $now)
            ->where(function ($sub) use ($now) {
                $sub->where('end_date', '>=', $now);
                $sub->orWhereNull('end_date');
            })
            ->count();

        $offline = OfflineCourse::where('status', '=', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->count();

        return ($online + $offline);
    }

    /*Lấy điểm cao nhất của học viên*/
    public function getUserMaxPoint()
    {
        $max_point = PromotionUserPoint::getMaxPoint();

        $user = Profile::query()
            ->select(['profile.user_id', 'user_point.point'])
            ->from('el_profile as profile')
            ->leftJoin('el_promotion_user_point as user_point', 'user_point.user_id', '=', 'profile.user_id')
            ->where('user_point.point', '=', $max_point)
            ->where('profile.status', '=', 1)
            ->whereNotIn('profile.user_id', function ($sub) {
                $sub->select(['user_id'])
                    ->from('el_training_teacher')
                    ->pluck('user_id')->toArray();
            })->first();

        return $user;
    }

    /*Lấy giảng viên có điểm cao nhất*/
    public function getTeacherMaxPoint()
    {
        $max_point = PromotionUserPoint::getMaxPoint();

        $user = Profile::query()
            ->select(['profile.user_id', 'user_point.point'])
            ->from('el_profile as profile')
            ->leftJoin('el_promotion_user_point as user_point', 'user_point.user_id', '=', 'profile.user_id')
            ->where('user_point.point', '=', $max_point)
            ->where('profile.status', '=', 1)
            ->whereIn('profile.user_id', function ($sub) {
                $sub->select(['user_id'])
                    ->from('el_training_teacher')
                    ->pluck('user_id')->toArray();
            })->first();

        return $user;
    }

    /*Lấy khóa học đã đăng ký theo năm chart*/

    public function getRegisterCourse()
    {
        $year = date('Y');
        for ($m = 1; $m <= 12; $m++) {
            $onlineRegister = TrainingProcess::where('user_id', \auth()->id())
                ->where('status', 1)
                ->where('course_type', 1)
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $offlineRegister = TrainingProcess::where('user_id', \auth()->id())
                ->where('status', 1)
                ->where('course_type', 2)
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $onlineComplete = TrainingProcess::where('user_id', \auth()->id())
                ->whereRaw('(pass = 0 or pass is null) ')
                ->where('status', 1)
                ->where('course_type', 1)
                ->where(\DB::raw('month(start_date)'), '=', $m)
                ->where(\DB::raw('year(start_date)'), '=', $year)->count();

            $offlineComplete = TrainingProcess::where('user_id', \auth()->id())
                ->where('pass', 1)
                ->where('course_type', 2)
                ->where(\DB::raw('year(start_date)'), '=', $year)
                ->where(\DB::raw('month(start_date)'), '=', $m)
                ->count();

            $totalQuiz = QuizRegister::where('user_id', \auth()->id())
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $totalQuizComplete = QuizResult::where('user_id', \auth()->id())
                ->where('result', 1)
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $online[] = $onlineRegister;
            $offline[] = $offlineRegister;
            $onl_complete[] = $onlineComplete;
            $off_complete[] = $offlineComplete;
            $quiz[] = $totalQuiz;
            $quizComplete[] = $totalQuizComplete;
        }
        $totalOnlineRegisterYear =  TrainingProcess::where('user_id', \auth()->id())
            ->where('course_type', 1)
            ->where('status', 1)
            ->where(\DB::raw('year(start_date)'), '=', $year)->count();

        $onl_complete_year = TrainingProcess::where('user_id', \auth()->id())
            ->where('course_type', 1)
            ->where('pass', 1)
            ->where(\DB::raw('year(start_date)'), '=', $year)->count();

        $onl_incomplete_year = $totalOnlineRegisterYear - $onl_complete_year;

        $totalOfflineRegisterYear = TrainingProcess::where('user_id', \auth()->id())
            ->where('course_type', 2)
            ->where('status', 1)
            ->where(\DB::raw('year(created_at)'), '=', $year)->count();

        $off_complete_year = TrainingProcess::where('user_id', \auth()->id())
            ->where('course_type', 2)
            ->where('pass', 1)
            ->where(\DB::raw('year(start_date)'), '=', $year)
            ->count();

        $off_incomplete_year = $totalOfflineRegisterYear - $off_complete_year;

        $data['online'] = $online;
        $data['offline'] = $offline;
        $data['onl_complete'] = $onl_complete;
        $data['off_complete'] = $off_complete;
        $data['quiz'] = $quiz;
        $data['quiz_complete'] = $quizComplete;
        $data['onl_year'] = [$onl_incomplete_year, $onl_complete_year];
        $data['off_year'] = [$off_incomplete_year, $off_complete_year];
        return $data;
    }

    public function search(Request $request)
    {
        $profile = Profile::find(Auth::id());
        $unit_user = Unit::getTreeParentUnit($profile->unit_code);

        $search = $request->input('search');
        $online = OnlineCourse::select(['id', 'code', 'name', 'image', 'start_date', 'end_date', 'register_deadline', \DB::raw('1 as type')])
            ->where(function ($sub) use ($search) {
                $sub->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('code', 'LIKE', '%' . $search . '%');
            });

        if (!Permission::isAdmin()) {
            $online->whereNull('unit_id');
            foreach ($unit_user as $item) {
                $online->orWhere('unit_id', 'like', '%' . $item->id . '%');
            }
        }

        $offline = OfflineCourse::select(['id', 'code', 'name', 'image', 'start_date', 'end_date', 'register_deadline', \DB::raw('2 as type')])
            ->where(function ($sub) use ($search) {
                $sub->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('code', 'LIKE', '%' . $search . '%');
            });

        if (!Permission::isAdmin()) {
            $offline->whereNull('unit_id');
            foreach ($unit_user as $item) {
                $offline->orWhere('unit_id', 'like', '%' . $item->id . '%');
            }
        }

        $result = $online->union($offline)->paginate(20);
        return view('data.search_result', ['items' => $result]);
    }

    /*khóa học theo lộ trình*/
    public function getCourseTrainingRoadmap()
    {
        $user_convert_titles = ConvertTitles::query()
            ->where('user_id', '=', \Auth::id())
            ->where('end_date', '>', date('Y-m-d H:i:s'))
            ->first();

        $user_potential = Potential::query()
            ->where('user_id', '=', \Auth::id())
            ->where('end_date', '>', date('Y-m-d H:i:s'))
            ->first();

        if ($user_convert_titles) {
            $roadmap = 'el_convert_titles_roadmap';
            $title = Titles::find($user_convert_titles->title_id);
        } elseif ($user_potential) {
            $roadmap = 'el_potential_roadmap';
            $user = Profile::find(\Auth::id());
            $title = Titles::where('code', '=', $user->title_code)->first();
        } else {
            $roadmap = 'el_trainingroadmap';
            $user = Profile::find(\Auth::id());
            $title = Titles::where('code', '=', $user->title_code)->first();
        }

        $subQuery = \DB::table('el_course_register_view as a1')
            ->join('el_course_view as a2', function ($join) {
                $join->on('a1.course_id', '=', 'a2.course_id');
                $join->on('a1.course_type', '=', 'a2.course_type');
            })
            ->where('a1.user_id', '=', \Auth::id())
            ->groupBy(['a2.subject_id', 'a2.course_type'])
            ->select([
                \DB::raw('MAX(' . \DB::getTablePrefix() . 'a2.course_id) as course_id'),
                'a2.subject_id',
                'a2.course_type'
            ]);

        $query = \DB::query();
        $query->select([
            'c.*'
        ]);

        $query->from("$roadmap AS a");
        if ($roadmap = 'el_trainingroadmap') {
            $query->joinSub($subQuery, 'b', function ($join) {
                $join->on('b.subject_id', '=', 'a.subject_id');
            });
        } else {
            $query->joinSub($subQuery, 'b', function ($join) {
                $join->on('b.course_type', '=', 'a.training_form');
                $join->on('b.subject_id', '=', 'a.subject_id');
            });
        }
        $query->join('el_course_view AS c', function ($join) {
            $join->on('c.course_id', '=', 'b.course_id');
            $join->on('c.course_type', '=', 'b.course_type');
        });
        $query->join('el_course_register_view as  d', function ($join) {
            $join->on('d.course_id', '=', 'c.course_id');
            $join->on('d.course_type', '=', 'c.course_type')->where('d.user_id', '=', \Auth::id());
        });
        $query->where('c.status', '=', 1);
        $query->where('c.isopen', '=', 1);
        $query->where('a.title_id', '=', @$title->id);

        return $query->limit(3)->get();
    }

    /*Lấy 5 khóa mới nhất*/
    public function getFiveCourseNew($limit = 5)
    {
        $now = date('Y-m-d H:i:s');
        $profile = Profile::find(Auth::id());
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $query = OnlineCourse::query()
            ->select([
                'id',
                'code',
                'name',
                'start_date',
                'end_date',
                'register_deadline',
                'image',
                DB::raw('1 AS type')
            ])
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->where(function ($sub) use ($now) {
                $sub->where('end_date', '>=', $now);
                $sub->orWhereNull('end_date');
            })
            ->whereIn('id', function ($sub) use ($title, $unit) {
                $sub->select(['course_id'])
                    ->from('el_online_object')
                    ->orWhere('unit_id', '=', @$unit->id)
                    ->orWhere('title_id', '=', @$title->id)
                    ->pluck('course_id')
                    ->toArray();
            });

        $offline = OfflineCourse::query()
            ->select([
                'id',
                'code',
                'name',
                'start_date',
                'end_date',
                'register_deadline',
                'image',
                DB::raw('2 AS type')
            ])
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->where(function ($sub) use ($now) {
                $sub->where('end_date', '>=', $now);
                $sub->orWhereNull('end_date');
            })
            ->whereIn('id', function ($sub) use ($title, $unit) {
                $sub->select(['course_id'])
                    ->from('el_offline_object')
                    ->orWhere('unit_id', '=', @$unit->id)
                    ->orWhere('title_id', '=', @$title->id)
                    ->pluck('course_id')
                    ->toArray();
            });

        $query = $query->union($offline);
        $query_sql = $query->toSql();
        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());
        $query->orderBy('start_date', 'DESC');
        $query->limit($limit);

        return $query->get();
    }

    public function chartCourseByUser()
    {
        $online_complete = OnlineResult::whereUserId(Auth::id())->where('result', '=', 1)->count();
        $online_uncomplete = OnlineResult::whereUserId(Auth::id())->where('result', '=', 0)->count();
        $online_not_learned = OnlineRegister::whereStatus(1)
            ->where('user_id', '=', Auth::id())
            ->whereNotIn('id', function ($sub) {
                $sub->select(['register_id'])
                    ->from('el_online_result')
                    ->pluck('register_id')
                    ->toArray();
            })
            ->count();

        $offline_complete = OfflineResult::whereUserId(Auth::id())->where('result', '=', 1)->count();
        $offline_uncomplete = OfflineResult::whereUserId(Auth::id())->where('result', '=', 0)->count();
        $offline_not_learned = OfflineRegister::whereStatus(1)
            ->where('user_id', '=', Auth::id())
            ->whereNotIn('id', function ($sub) {
                $sub->select(['register_id'])
                    ->from('el_offline_result')
                    ->pluck('register_id')
                    ->toArray();
            })
            ->count();

        $not_learned = $online_not_learned + $offline_not_learned;
        $uncomplete = $online_uncomplete + $offline_uncomplete;
        $complete = $online_complete + $offline_complete;

        $data['course_by_user'] = [$not_learned, $uncomplete, $complete];
        return $data;
    }

    /*Chuyên đề của nhân viên*/
    public function getDataRoadmap()
    {
        $user_id = Auth::id();
        $user = \DB::table('el_profile_view')->where(['user_id' => $user_id])->first();
        $subQuery = \DB::table('el_training_process')
            ->where('user_id', '=', $user_id)
            ->where('titles_code', '=', $user->title_code)
            ->groupBy('subject_id')
            ->select([
                \DB::raw('MAX(id) as id'),
                'subject_id',
            ]);

        $query = \DB::query();
        $query->select([
            'c.id',
            'c.subject_name',
            'c.pass',
        ]);
        $query->from("el_trainingroadmap AS a");
        $query->joinSub($subQuery, 'b', function ($join) {
            $join->on('b.subject_id', '=', 'a.subject_id');
        });
        $query->leftJoin('el_training_process as c', function ($join) {
            $join->on('c.id', '=', 'b.id');
        });
        $query->where('a.title_id', '=', $user->title_id);
        $count = $query->count();
        $rows = $query->get();

        foreach ($rows as $row) {
            if ($row->pass == 1) {
                $row->status = trans('backend.finish');
            } else {
                $row->status = trans('backend.incomplete');
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function chartSubjectByUser()
    {
        $user_id = Auth::id();
        $user = \DB::table('el_profile_view')->where(['user_id' => $user_id])->first();
        $subQuery = \DB::table('el_training_process')
            ->where('user_id', '=', $user_id)
            ->where('titles_code', '=', $user->title_code)
            ->groupBy('subject_id')
            ->select([
                \DB::raw('MAX(id) as id'),
                'subject_id',
            ]);

        $query = \DB::query();
        $query->select([
            'c.pass',
        ]);
        $query->from("el_trainingroadmap AS a");
        $query->joinSub($subQuery, 'b', function ($join) {
            $join->on('b.subject_id', '=', 'a.subject_id');
        });
        $query->leftJoin('el_training_process as c', function ($join) {
            $join->on('c.id', '=', 'b.id');
        });
        $query->where('a.title_id', '=', $user->title_id);
        $rows = $query->get();

        $uncomplete = 0;
        $complete = 0;
        foreach ($rows as $row) {
            if ($row->pass == 1) {
                $complete += 1;
            } else {
                $uncomplete += 1;
            }
        }
        $data = [$uncomplete, $complete];

        return $data;
    }

    public function getLevelSubjectByUser()
    {
        $dbprefix = \DB::getTablePrefix();
        $user_id = Auth::id();
        $user = \DB::table('el_profile_view')->where(['user_id' => $user_id])->first();
        $subQuery = TrainingProcess::query()
            ->select(['subject_id'])
            //->where('user_id','=', $user_id)
            ->where('titles_code', '=', $user->title_code)
            ->groupBy('subject_id')
            ->pluck('subject_id')->toArray();

        $level_subject = LevelSubject::query()
            ->select([
                \DB::raw('MAX(' . $dbprefix . 'a.id) as id'),
                'a.name'
            ])
            ->from('el_level_subject as a')
            ->leftJoin('el_subject as b', 'b.level_subject_id', '=', 'a.id')
            //->leftJoin('el_trainingroadmap as c', 'c.subject_id', '=', 'b.id')
            //->where('c.title_id','=', $user->title_id)
            ->whereIn('b.id', $subQuery)
            ->groupBy('a.name')
            ->get();

        return $level_subject;
    }

    public function closeOpendMenuBottom(Request $request)
    {
        session(['close_open_menu' => $request->status]);
        session()->save();
    }

    public function closeOpendMenu(Request $request)
    {
        session(['close_open_menu_frontend' => $request->status]);
        session()->save();
    }
}
