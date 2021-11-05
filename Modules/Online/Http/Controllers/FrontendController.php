<?php

namespace Modules\Online\Http\Controllers;

use App\Automail;
use App\Config;
use App\Helpers\VideoStream;
use App\Models\Categories\LevelSubject;
use App\Profile;
use App\ProfileView;
use App\Events\Online\GoActivity;
use App\Models\Categories\Unit;
use App\Permission;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\LogViewCourse\Entities\LogViewCourse;
use Modules\Notify\Entities\Notify;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineRating;
use Modules\Online\Entities\OnlineCourseNote;
use Modules\Online\Entities\OnlineRatingLevel;
use Modules\Online\Entities\OnlineRatingLevelObject;
use Modules\Online\Entities\OnlineRegister;
use Illuminate\Support\Facades\Auth;
use Modules\PointHist\Entities\PointHist;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionShare;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Rating\Entities\RatingCourse;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\RefererHist\Entities\RefererRegisterCourse;
use Modules\User\Entities\TrainingProcess;
use App\Models\Categories\Titles;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineCourseLesson;
use Modules\Online\Entities\OnlineCourseComplete;
use App\EmulationPromotion;
use Modules\Online\Entities\OnlineCourseActivityFile;
use Modules\Online\Entities\OnlineCourseActivityVideo;
use Modules\Online\Entities\OnlineCourseActivityUrl;
use Modules\Online\Entities\OnlineCourseActivityQuiz;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineViewActivity;
use Modules\Online\Entities\OnlineResult;
use App\UserViewCourse;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Jenssegers\Agent\Agent;

class FrontendController extends Controller
{
    public function index(Request $request) {
        $items = $this->getItems($request);

        return view('online::frontend.index', [
            'items' => $items,
        ]);
    }

    public function getItems(Request $request) {
        $search = $request->get('q');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');
        $training_program_id = $request->get('training_program_id');
        $level_subject_id = $request->get('level_subject_id');
        $subject_id = $request->get('subject_id');
        $status = $request->get('status');
        $user_id = Auth::id();

        $profile = Profile::find(Auth::id());
        $unit_user = Unit::getTreeParentUnit($profile->unit_code);

        $query = OnlineCourse::query();
        $query->select(['a.*']);
        $query->from('el_online_course as a');
        if($status && $status !== 5 && $status !== 4) {
            $query->leftjoin('el_online_register as b','b.course_id','=','a.id');
        }
        $query->where('a.status', '=', 1);
        $query->where('a.isopen', '=', 1);

        if (!Permission::isAdmin() && !$status){
            $query->orWhereNull('unit_id');
            $query->where(function ($sub) use ($unit_user){
                $sub->whereNotNull('unit_id');
                foreach ($unit_user as $item){
                    $sub->orWhere('unit_id', 'like', '%'.$item->id.'%');
                }
            });
        }

        $get_course_id_register = OnlineRegister::where('user_id',$user_id)->pluck('course_id')->toArray();
        $get_course_id_complete = OnlineCourseComplete::where('user_id',$user_id)->pluck('course_id')->toArray();

        if($status && $status == 1) {
            $query->whereNotIn('a.id', $get_course_id_register);

            $query->where(function ($sub){
                $sub->whereNull('end_date');
                $sub->orWhere('end_date', '>', date('Y-m-d'));
            });

        } elseif($status && $status == 2) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',1);
        } elseif($status && $status == 3) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',2);
        } elseif($status && $status == 4) {
            $query->leftjoin('el_online_course_complete as c','c.course_id','=','a.id');
            $query->whereIn('a.id',$get_course_id_complete);
        } elseif($status && $status == 5) {
            $query->where('a.end_date', '<=', date('Y-m-d'));
        }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('a.code', 'like', '%'. $search .'%');
                $subquery->orWhere('a.name', 'like', '%'. $search .'%');
            });
        }

        if ($fromdate) {
            $query->where('a.start_date', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where('a.start_date', '<=', date_convert($todate, '23:59:59'));
        }

        if ($training_program_id) {
            $query->where('a.training_program_id', '=', $training_program_id);
        }

        if ($level_subject_id){
            $query->where('a.level_subject_id', '=', $level_subject_id);
        }

        if ($subject_id) {
            $query->where('a.subject_id', '=', $subject_id);
        }

        $query->orderByDesc('a.id');
        $items = $query->paginate(20);
        $items->appends($request->query());

        return $items;
    }

    public function getData(Request $request) {
        $search = $request->get('search');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');
        $training_program_id = $request->get('training_program_id');
        $subject_id = $request->get('subject_id');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourse::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }

        if ($fromdate) {
            $query->where('start_date', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where('start_date', '<=', date_convert($todate, '23:59:59'));
        }

        if ($training_program_id) {
            $query->where('training_program_id', '=', $training_program_id);
        }

        if ($subject_id) {
            $query->where('subject_id', '=', $subject_id);
        }

        //$query->orderByDesc('id');
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function detail($id, Request $request){
        if ($request->share_key){
            $user_share = PromotionShare::query()
                ->where('course_id', '=', $id)
                ->where('type', '=', 1)
                ->where('share_key', '=', $request->share_key)
                ->first();

            if ($user_share->user_id != Auth::id()){
                $setting = PromotionCourseSetting::where('course_id', $id)
                    ->where('type', 1)
                    ->where('status', 1)
                    ->where('code', '=', 'share_course')
                    ->first();
                if ($setting && $setting->point){
                    $user_point = PromotionUserPoint::firstOrCreate([
                        'user_id' => $user_share->user_id,
                        'user_type' => getUserType(),
                    ], [
                        'point' => 0,
                        'level_id' => 0
                    ]);
                    $user_point->point += $setting->point;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_share->user_id);
                    $user_point->update();

                    $this->saveHistoryPromotion($user_share->user_id, $setting->point, $setting->course_id, $setting->id);
                    $this->savePromotionEmulation($user_share->user_id, $setting->point,$setting->course_id, 1, $setting->id);
                }
            }

            return redirect()->route('module.online.detail', ['id' => $id]);
        }
        $user_id = Auth::id();
        $course = OnlineCourse::where('id', '=', $id)
            ->where('isopen', '=', 1)
            ->where('status', '=', 1)
            ->firstOrFail();

        if($course->auto == 2) {
            $this->autoRegisterCourse($user_id, $id);
        }

        OnlineCourse::updateItemViews($id);

        $register = OnlineRegister::where('user_id', '=', $user_id)
            ->where('course_id', '=', $id)
            ->where('status', '=', 1)
            ->first();
        $categories = OnlineCourse::getCourseCategory($course->training_program_id, $course->id);

        $profile = Profile::where('user_id', '=', $user_id)->first();
        $rating_course = RatingCourse::where('course_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', 1)
            ->first();

        $rating_star = OnlineRating::where('course_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->first();

        $training_process = TrainingProcess::where('course_id',$id)->where('user_id',\Auth::id())->where('course_type',1)->first();

        $lessons_course = OnlineCourseLesson::where('course_id',$id)->get();

        $part = function ($subject_id){
            $user_type = Quiz::getUserType();
            $item = QuizPart::where('quiz_id', '=', $subject_id)
                ->whereIn('id', function ($subquery) use ($user_type, $subject_id) {
                    $subquery->select(['a.part_id'])
                        ->from('el_quiz_register AS a')
                        ->join('el_quiz_part AS b', 'b.id', '=', 'a.part_id')
                        ->where('a.quiz_id', '=', $subject_id)
                        ->where('a.user_id', '=', getUserId())
                        ->where('a.type', '=', $user_type)
                        ->where(function ($where){
                            $where->orWhere('b.end_date', '>', date('Y-m-d H:i:s'));
                            $where->orWhereNull('b.end_date');
                        });
                })->first();
            return $item;
        };

        $course_time = $course->course_time;
        $course_time_unit = $course->course_time_unit;
        $this->updateLogViewCourse($course);
        return view('online::frontend.detail', [
            'item' => $course,
            'training_process' => $training_process,
            'categories' => $categories,
            'profile' => $profile,
            'rating_course' => $rating_course,
            'rating_star' => $rating_star,
            // 'activities' => $course->getActivities(),
            'part' => $part,
            'course_time' => $course_time,
            'course_time_unit' => $course_time_unit,
            'register' => $register,
            'lessons_course' => $lessons_course
        ]);
    }
    public function updateLogViewCourse(OnlineCourse $course)
    {
        $user_id = Auth::id();
        $session_id = \Session::getId();
        $ip_address = \request()->ip();
        $user_agent = \request()->userAgent();
        $profile = ProfileView::find($user_id);
        $user_full_name = $profile->full_name;
        $user_code = $profile->code;
        LogViewCourse::updateOrCreate(
            [
                'user_id'=>$user_id,
                'session_id'=>$session_id,
                'course_id'=>$course->id,
                'course_type'=>1
            ],
            [
                'user_code' =>$user_code,
                'course_code'=>$course->code,
                'course_name'=>$course->name,
                'ip_address'=>$ip_address,
                'user_agent'=>$user_agent,
                'user_name'=>$user_full_name,
                'last_access'=>now(),
            ]
        );
    }
    public function registerCourse($id, Request $request){
        $course = OnlineCourse::findOrFail($id);
        $referer = $request->post('referer');
        $user_id = Auth::id();
        $profile_unit = ProfileView::where('user_id',$user_id)->first();
        $model = OnlineRegister::firstOrCreate(['user_id'=>Auth::id(),'course_id'=>$id]);
        $model->user_id = Auth::id();
        $model->course_id = $id;
        $model->unit_by = $profile_unit->unit_id;

        // xét điều kiện đăng ký bắt buộc
        $profile_user = ProfileView::where('user_id',\Auth::id())->first();
        $online_objects = OnlineObject::where('course_id',$id)->where('type',1)->get();
        if (!empty($online_objects)) {
            foreach ($online_objects as $key => $online_object) {
                if ($online_object->title_id !== null && $online_object->title_id == $profile_user->title_id && $online_object->unit_id == $profile_user->unit_id) {
                    $model->status = 1;
                } else if ($profile_user->unit_id !== null && $online_object->unit_id == $profile_user->unit_id) {
                    $model->status = 1;
                }
            }
        }
        !empty($course->title_join_id) ? $get_title_join_model_id = json_decode($course->title_join_id) : $get_title_join_model_id = [];
        !empty($course->title_recommend_id) ? $get_title_recommend_model_id = json_decode($course->title_recommend_id) : $get_title_recommend_model_id = [];

        if( (!empty($get_title_join_model_id) && !in_array(0, $get_title_join_model_id) && !in_array($profile_user->title_id, $get_title_join_model_id)) ||
            (!empty($get_title_recommend_model_id) && !in_array(0, $get_title_recommend_model_id) && !in_array($profile_user->title_id, $get_title_recommend_model_id)) ) {
            json_result([
                'status' => 'warning',
                'message' => 'Anh chị không thuộc đối tượng tham gia khóa học. Vui lòng liên hệ Trung tâm đào tạo',
            ]);
        }

        /* insert hist point  register referer*/
        if ($referer) {
            if (Profile::validRefer($referer)) {
                RefererRegisterCourse::saveReferRegisterOnlineCourse($id,$referer);
                PromotionUserPoint::updatePointRegisterCourse($referer);
                PointHist::savePointHist($referer);
            }else{
                json_message('Mã người giới thiệu không hợp lệ','error');
            }
        }
        if ($course->auto == 2) {
            $model->status = 1;
        } else {
            $model->status = 2;
        }

        $quizs = Quiz::where('course_id', '=', $id)
            ->where('status', '=', 1)
            ->get();

        foreach ($quizs as $quiz){
            $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
            if ($quiz_part) {
                $query = QuizRegister::where('quiz_id', '=', $quiz->id)
                    ->where('user_id', '=', Auth::id())
                    ->where('type', '=', 1);

                if ($query->exists()) {
                    $query->update([
                        'part_id' => $quiz_part->id
                    ]);
                }else {
                    $query->insert([
                        'quiz_id' => $quiz->id,
                        'user_id' => Auth::id(),
                        'part_id' => $quiz_part->id,
                        'type' => 1,
                    ]);
                }
            }else{
                continue;
            }
        }
        $save = $model->save();
        if ($save) {
            $get_las_register = OnlineRegister::select('status')->where('id',$model->id)->first();
            //update training process
            $subject = Subject::findOrFail($course->subject_id);
            $profile = \DB::table('el_profile_view')->where('user_id','=',$user_id)->first();
            $title = Titles::where('id',$profile->title_id)->first();
            TrainingProcess::updateOrCreate(
                [
                    'user_id'=>$user_id,
                    'course_id'=>$id,
                    'course_type'=>1
                ],
                [
                    'course_code'=>$course->code,
                    'course_name'=>$course->name,
                    'subject_id'=>$subject->id,
                    'subject_code'=>$subject->code,
                    'subject_name'=>$subject->name,
                    'titles_code'=>$title ? $title->code : null,
                    'titles_name'=>$profile->title_name,
                    'unit_code'=>$profile->unit_code,
                    'unit_name'=>$profile->unit_name,
                    'start_date'=>$course->start_date,
                    'end_date'=>$course->end_date,
                    'process_type'=>1,
                    'certificate'=>$course->cert_code,
                    'status'=> $get_las_register->status == 2 ? 0 : 1,
                ]
            );
            ////
            $this->sendMailManagerApprove($id,$model->id);
            if (url_mobile()){
                json_result([
                    'status' => 'success',
                    'message' => 'Đăng ký thành công',
                    'redirect' => route('themes.mobile.frontend.online.detail', [
                        'course_id' => $id
                    ])
                ]);
            }
            if ($get_las_register->status == 2) {
                return json_result([
                    'status' => 'success',
                    'message' => 'Đăng ký khóa học cần xét duyệt để tham gia',
                    // 'redirect' => route('module.online'),
                    'redirect' => route('frontend.all_course',['type' => 0])
                ]);
            } else {
                return json_result([
                            'status' => 'success',
                            'message' => 'Đăng ký thành công',
                            // 'redirect' => route('module.online')
                            'redirect' => route('frontend.all_course',['type' => 0])
                        ]);
            }
        }
    }

    public function sendMailManagerApprove($course_id,$register_id)
    {
        $user_id = Auth::id();
        $profile = Profile::where(['user_id'=>$user_id])->selectRaw("unit_id, concat(lastname,' ',firstname) as fname, unit_code")->firstOrFail();
        $full_name = $profile->fname;
        $course = OnlineCourse::find($course_id);
        $unit_id = $profile->unit_id;
        //truong don vi
        $user_managers = \DB::query()->from('el_unit_manager as a')->join('el_unit as b','a.unit_code','=','b.code')->join('el_profile as c','c.code','=','a.user_code')
            ->where(['b.id'=>$unit_id])->select('c.user_id')->get();
        foreach ($user_managers as $user){
            $signature = getMailSignature($user->user_id);
            $params = [
                'signature' => $signature,
                'code' => $course->code,
                'course_name' => $course->name,
                'student' => $full_name,
                'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 1])
            ];
            $this->execMailManagerApprove($params,[$user->user_id],$register_id);
        }
        //duoc phan quyen quan ly don vi
        $unit_id_arr = [];
        $unit = Unit::getTreeParentUnit($profile->unit_code);
        foreach ($unit as $item){
            $unit_id_arr[] = $item->id;
        }
        $users= \DB::query()
            ->select('d.user_id')
            ->from('el_permission_type_unit as a')
            ->join('el_permission_type as b','a.permission_type_id','b.id')
            ->join('el_user_permission_type as c','c.permission_type_id','a.permission_type_id')
            ->join('el_profile as d','d.user_id','c.user_id')
            ->join('el_permissions as e','e.id','c.permission_id')
            ->whereIn('d.unit_id',$unit_id_arr)
            ->whereIn('a.unit_id',$unit_id_arr)
            ->whereIn('e.name', function ($sub2){
                $sub2->select(['per.parent'])
                    ->from('el_model_has_permissions as model')
                    ->leftJoin('el_permissions as per', 'per.id', '=', 'model.permission_id')
                    ->whereColumn('model.model_id', '=', 'c.user_id')
                    ->where('per.name', '=', 'online-course-register-approve');
            })
            ->where(['e.name'=>'online-course-register'])
            ->get();

        foreach ($users as $user){
            $signature = getMailSignature($user->user_id);
            $params = [
                'signature' => $signature,
                'code' => $course->code,
                'course_name' => $course->name,
                'student' => $full_name,
                'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 1])
            ];
            $this->execMailManagerApprove($params,[$user->user_id],$register_id);
        }
    }

    public function execMailManagerApprove(array $params, array $user_id,int $register_id)
    {
        $automail = new Automail();
        $automail->template_code = 'approve_register';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->object_id = '1'.$register_id;
        $automail->object_type = 'approve_online_register';
        $automail->addToAutomail();
    }

    public function rating($id, Request $request) {
        $this->validateRequest([
            'star' => 'required|min:1',
        ], $request, ['star' => 'Sao']);

        $user_id = getUserId();
        if (OnlineRating::getRating($id, $user_id)) {
            json_message('Bạn đã đánh giá khóa học này', 'warning');
        }

        $model = new OnlineRating();
        $model->course_id = $id;
        $model->user_id = $user_id;
        $model->user_type = getUserType();
        $model->num_star = $request->star;

        if ($model->save()) {
            $setting = PromotionCourseSetting::where('course_id', $id)
                ->where('type', 1)
                ->where('status', 1)
                ->where('code', '=', 'rating_star')
                ->first();
            if ($setting && $setting->point){
                $user_point = PromotionUserPoint::firstOrCreate([
                    'user_id' => $user_id,
                    'user_type' => getUserType(),
                ], [
                    'point' => 0,
                    'level_id' => 0
                ]);
                $user_point->point += $setting->point;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_id);
                $user_point->update();

                $this->saveHistoryPromotion($user_id, $setting->point, $setting->course_id, $setting->id);
                $this->savePromotionEmulation($user_id, $setting->point, $setting->course_id, 1, $setting->id);
            }

            return response()->json(['message' => "Cảm ơn bạn đã đánh giá"]);
        }

        return response()->json(['message'=>'Đã có lỗi sảy ra vui lòng thử lại', 'status'=>'error']);
    }

    public function search(Request $request) {
        $items = $this->getItems($request);
        $training_program = TrainingProgram::find($request->get('training_program_id'));
        $level_subject = LevelSubject::find($request->get('level_subject_id'));
        $subject = Subject::find($request->get('subject_id'));
        $status = $request->status;

        return view('online::frontend.index', [
            'items' => $items,
            'training_program' => $training_program,
            'subject' => $subject,
            'level_subject' => $level_subject,
            'status' => $request->status,
        ]);
    }

    public function goActivity($course_id, $course_activity_id,$lesson) {
        $course_activity = OnlineCourseActivity::findOrFail($course_activity_id);
        $link = $course_activity->getLink($lesson);
        if (empty($link)) {
            return abort(404);
        }

        event(new GoActivity($course_id, $course_activity_id));

        return redirect()->to($link);
    }

    public function viewPDF($id, Request $request){
        if (url_mobile()){
            $url = route('themes.mobile.frontend.online.detail', ['course_id' => $id]);
        }else{
            $url = route('module.online.detail', ['id' => $id]);
        }
        $course = OnlineCourse::find($id);
        if ($request->get('path')){
            $path = $request->get('path');
        }else{
            $path = upload_file($course->document);
        }

        return view('online::frontend.view_pdf', [
            'path' => $path,
            'url' => $url,
        ]);
    }

    public function viewVideo($file) {
        $file = decrypt_array($file);
        if (!isset($file['path'])) {
            return abort(404);
        }

        if (!file_exists($file['path'])) {
            return abort(404);
        }

        $stream = new VideoStream($file['path']);
        $stream->start();
    }

    // XEM PDF HƯỚNG DẪN HỌC
    public function tutorialViewPDF($id,$key, Request $request){
        if (url_mobile()){
            $url = route('themes.mobile.frontend.online.detail', ['course_id' => $id]);
        }else{
            $url = route('module.online.detail', ['id' => $id]);
        }
        $course = OnlineCourse::find($id);
        $get_tutorials = json_decode($course->tutorial);

        foreach ($get_tutorials as $key_tutorial => $value) {
            if ($key_tutorial == $key) {
                $path = upload_file($value);
            }
        }
        return view('online::frontend.view_pdf', [
            'path' => $path,
            'url' => $url,
        ]);
    }
    // END XEM PDF HƯỚNG DẪN HỌC

    public function showModalQrcodeReferer(Request $request) {
        $course_id = $request->input('course_id');
        return view('online::modal.qrcode_referer' );
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=', $point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function saveHistoryPromotion($user_id,$point,$course_id, $promotion_course_setting_id){
        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->user_type = getUserType();
        $history->point = $point;
        $history->type = 1;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        $course_name = OnlineCourse::query()->find($course_id)->name;

        $model = new Notify();
        $model->user_id = $user_id;
        $model->subject = 'Thông báo đạt điểm thưởng khoá học.';
        $model->content = 'Bạn đã đạt điểm thưởng là "'. $point .'" điểm của khoá học "'. $course_name .'"';
        $model->url = null;
        $model->created_by = 0;
        $model->save();

        $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
        $redirect_url = route('module.notify.view', [
            'id' => $model->id,
            'type' => 1
        ]);

        $notification = new AppNotification();
        $notification->setTitle($model->subject);
        $notification->setMessage($content);
        $notification->setUrl($redirect_url);
        $notification->add($user_id);
        $notification->save();
    }

    private function savePromotionEmulation($user_id,$point,$course_id, $type, $setting){
        $history = EmulationPromotion::firstOrNew(['course_id' => $course_id, 'user_id' => $user_id, 'type' => $type, 'course_setting_id' => $setting]);
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 1;
        $history->course_id = $course_id;
        $history->course_setting_id = $setting;
        $history->save();
    }

    public function shareCourse($course_id, $type, Request $request){
        $promotion_share = PromotionShare::firstOrNew([
            'course_id' => $course_id,
            'type' => $type,
            'user_id' => getUserId(),
            'user_type' => getUserType(),
        ]);
        $promotion_share->share_key = $request->share_key;
        $promotion_share->save();
        json_result([
            'key' => $request->share_key,
        ]);;
    }

    public function autoRegisterCourse($user_id, $course_id) {
        $course = OnlineCourse::findOrFail($course_id);
        $subject = Subject::findOrFail($course->subject_id);
        $profile = \DB::table('el_profile_view')->where('user_id','=',$user_id)->first();
        // dd($profile->code);
        TrainingProcess::updateOrCreate(
            [
                'user_id'=>$user_id,
                'course_id'=>$course_id,
                'course_type'=>1
            ],
            [
                'course_code'=>$course->code,
                'course_name'=>$course->name,
                'subject_id'=>$subject->id,
                'subject_code'=>$subject->code,
                'subject_name'=>$subject->name,
                'titles_code'=>$profile->title_code,
                'titles_name'=>$profile->title_name,
                'unit_code'=>$profile->unit_code,
                'unit_name'=>$profile->unit_name,
                'start_date'=>$course->start_date,
                'end_date'=>$course->end_date,
                'process_type'=>1,
                'certificate'=>$course->cert_code,
            ]
        );
        $model = OnlineRegister::firstOrNew(['user_id' => $user_id, 'course_id' => $course_id]);
        $model->user_id = $user_id;
        $model->course_id = $course_id;
        $model->status = 1;
        $quizs = Quiz::where('course_id', '=', $course_id)->where('status', '=', 1)->get();
        if ($quizs){
            foreach ($quizs as $quiz){
                $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                if ($quiz_part){
                    $query = QuizRegister::where('quiz_id', '=', $quiz->id)
                        ->where('user_id', '=', $user_id)
                        ->where('type', '=', 1);
                    if ($query->exists()) {
                        $query->update([
                            'part_id' => $quiz_part->id
                        ]);
                    }else {
                        $query->insert([
                            'quiz_id' => $quiz->id,
                            'user_id' => $user_id,
                            'part_id' => $quiz_part->id,
                            'type' => 1,
                        ]);
                    }
                }else{
                    continue;
                }
            }
        }
        $model->save();
        return;
    }

    public function detail2($id, Request $request){
        $user_id = Auth::id();
        OnlineCourse::updateItemViews($id);

        $course = OnlineCourse::where('id', '=', $id)
        ->where('isopen', '=', 1)
        ->where('status', '=', 1)
        ->firstOrFail();

        if($course->auto == 2) {
            $this->autoRegisterCourse($user_id, $id);
        }

        $time_user_view_course = UserViewCourse::updateOrCreate([
            'course_id' => $id,
            'course_type' => 1,
            'user_id' => $user_id,
        ], [
            'course_id' => $id,
            'course_type' => 1,
            'user_id' => $user_id,
            'time_view' => date('Y-m-d H:i'),
        ]);

        $check_register = OnlineRegister::where('course_id',$id)->where('user_id',$user_id)->first();

        $date_join = OnlineCourseActivityHistory::select('created_at')->where('course_id',$id)->where('user_id',$user_id)->first();

        $get_result = OnlineResult::where('course_id',$id)->where('user_id',$user_id)->first();

        $profile = ProfileView::where('user_id', '=', $user_id)->first();

        $lessons_course = OnlineCourseLesson::where('course_id',$id)->get();

        $condition_activity = OnlineCourseCondition::where('course_id',$id)->first();
        if(!empty($condition_activity)) {
            $condition_activity = explode(',',$condition_activity->activity);
        }
        $id_activity_scorm = '';
        $type_activity = 0;
        $link = '';
        $check_activity_active = OnlineViewActivity::where('course_id',$id)->where('user_id',$user_id)->first();
        $get_first_activity= '';
        $get_activity_courses = OnlineCourseActivity::where('course_id',$id)->get();

        if(!empty($check_register) && $check_register->status == 1) {
            if(!empty($check_activity_active)) {
                $get_first_activity = OnlineCourseActivity::where('id',$check_activity_active->activity_id)->first();
            } else {
                $get_first_activity = OnlineCourseActivity::where('course_id',$id)->first();
                if ($get_first_activity){
                    event(new GoActivity($id, @$get_first_activity->id));
                }
            }
            $check_type_activity = $get_first_activity ? $get_first_activity->activity_id : '';
            if($check_type_activity == 3) {
                $file = OnlineCourseActivityFile::where('id', '=', $get_first_activity->subject_id)->first();
                $file_path = upload_file(explode('|', $file->path)[0]);
                $link = route('module.online.view_pdf', [$id]).'?path='. $file_path;
            } else if ($check_type_activity == 4) {
                $file = OnlineCourseActivityUrl::find($get_first_activity->subject_id);
                $link = $file->url;
                if (is_youtube_url($link)) {
                    $link = 'https://www.youtube.com/embed/' . get_youtube_id($link);
                }
            } else if ($check_type_activity == 5) {
                $file = OnlineCourseActivityVideo::find($get_first_activity->subject_id);
                $link = upload_file($file->path);
            } else if ($check_type_activity == 1) {
                $link = OnlineCourseActivityScorm::find($get_first_activity->subject_id);
                $id_activity_scorm = @$link->id;
                $type_activity = 1;
            } else if ($check_type_activity == 2) {
                $link = $get_first_activity->getLinkQuizCourse($get_first_activity->lesson_id);
                $type_activity = 2;
            }
        }
        $activeLession = OnlineCourseActivity::where(['course_id'=>$id,'subject_id'=>$id_activity_scorm])->value('lesson_id');
        $count_rating_level = $query = OnlineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($id){
                $sub->select(['online_rating_level_id'])
                    ->from('el_online_rating_level_object')
                    ->where('course_id', '=', $id)
                    ->where('object_type', '=', 1)
                    ->where(function ($sub2){
                        $sub2->orWhereNull('end_date');
                        $sub2->orWhere('end_date', '>=', now());
                    })
                    ->pluck('online_rating_level_id')
                    ->toArray();
            })
            ->whereExists(function ($sub2) use ($id, $user_id){
                $sub2->select(['id'])
                    ->from('el_online_register')
                    ->where('user_id', '=', $user_id)
                    ->where('course_id', '=', $id)
                    ->where('status', '=', 1);
            })
            ->where('course_id', '=', $id)
            ->whereNotExists(function ($sub) use ($user_id, $id){
                $sub->select(['id'])
                    ->from('el_rating_level_course as rlc')
                    ->whereColumn('rlc.course_rating_level_id', '=', 'el_online_rating_level.id')
                    ->where('rlc.user_id', '=', $user_id)
                    ->where('rlc.user_type', '=', 1)
                    ->where('rlc.course_id', '=', $id)
                    ->where('rlc.course_type', '=', 1);
            })
            ->count();

        $this->updateLogViewCourse($course);
        $agent = new Agent();
        return view('online::frontend.detail2', [
            'item' => $course,
            'profile' => $profile,
            'lessons_course' => $lessons_course,
            'link' => $link,
            'type_activity' => $type_activity,
            'get_first_activity' => $get_first_activity,
            'id_activity_scorm' => $id_activity_scorm,
            'activeLession' => $activeLession,
            'get_result' => $get_result,
            'time_user_view_course' => $time_user_view_course,
            'date_join' => $date_join,
            'get_activity_courses' => $get_activity_courses,
            'condition_activity' => $condition_activity,
            'check_register' => $check_register,
            'check_activity_active' => !empty($check_activity_active) ? $check_activity_active->id : 0,
            'count_rating_level' => $count_rating_level,
            'agent' => $agent
        ]);
    }

    public function ajaxActivity(Request $request){
        $course_id = $request->id;
        $activity_id = $request->aid;
        $lesson_id = $request->lesson_id;
        $type = $request->type;
        $user_id = Auth::id();

        $query = OnlineViewActivity::updateOrCreate([
            'course_id' => $course_id,
            'user_id' => $user_id,
        ], [
            'course_id' => $course_id,
            'activity_id' => $activity_id,
            'user_id' => $user_id,
        ]);

        event(new GoActivity($course_id, $activity_id));

        $course_activity = OnlineCourseActivity::findOrFail($activity_id);
        if($type == 3) {
            $file = OnlineCourseActivityFile::where('id', '=', $course_activity->subject_id)->first();
            $file_path = upload_file(explode('|', $file->path)[0]);
            $link = route('module.online.view_pdf', [$course_id]).'?path='. $file_path;
        } else if ($type == 4) {
            $file = OnlineCourseActivityUrl::find($course_activity->subject_id);
            $link = $file->url;
            if (is_youtube_url($link)) {
                $link = 'https://www.youtube.com/embed/' . get_youtube_id($link);
            }
        } else if ($type == 5) {
            $file = OnlineCourseActivityVideo::find($course_activity->subject_id);
            $link = upload_file($file->path);
        } else if ($type == 1) {
            $link = OnlineCourseActivityScorm::findOrFail($course_activity->subject_id);
        } else if($type == 2) {
            $course_activity = OnlineCourseActivity::findOrFail($activity_id);
            $link = $course_activity->getLinkQuizCourse($lesson_id);
        }

        if (empty($link)) {
            return abort(404);
        }

        return json_result([
            'link' => $link,
            'course_activity' => $course_activity,
        ]);
    }

    public function getDataRatingLevel($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $course = OnlineCourse::find($course_id);
        $user_id = getUserId();

        $query = OnlineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($course_id){
                $sub->select(['online_rating_level_id'])
                    ->from('el_online_rating_level_object')
                    ->where('course_id', '=', $course_id)
                    ->where('object_type', '=', 1)
                    ->pluck('online_rating_level_id')
                    ->toArray();
            })
            ->whereExists(function ($sub2) use ($course_id, $user_id){
                $sub2->select(['id'])
                    ->from('el_online_register')
                    ->where('user_id', '=', $user_id)
                    ->where('course_id', '=', $course_id)
                    ->where('status', '=', 1);
            })
            ->where('course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy($sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $check = [];
            $start_date_rating = '';
            $end_date_rating = '';
            $rating_level_url = '';
            $rating_status = 0;
            $user_completed = 0;
            $user_result = 0;

            $rating_level_object = OnlineRatingLevelObject::query()
                ->where('course_id', '=', $course_id)
                ->where('online_rating_level_id', '=', $row->id)
                ->where('object_type', '=', 1)
                ->first();
            if ($rating_level_object){
                $result = OnlineResult::query()
                    ->where('course_id', '=', $course_id)
                    ->where('user_id', '=', Auth::id())
                    ->where('result', '=', 1)
                    ->first();
                if ($result){
                    $user_result = 1;
                }

                if ($rating_level_object->time_type == 1){
                    $start_date_rating = $rating_level_object->start_date;
                    $end_date_rating = $rating_level_object->end_date;
                }
                if ($rating_level_object->time_type == 2){
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($course->start_date)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating= $course->start_date;
                    }
                }
                if ($rating_level_object->time_type == 3){
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($course->end_date)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating = $course->end_date;
                    }
                }
                if ($rating_level_object->time_type == 4){
                    if ($result){
                        if (isset($rating_level_object->num_date)){
                            $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$rating_level_object->num_date} day";
                        }else{
                            $start_date_rating = $result->created_at;
                        }
                    }
                }
                if($rating_level_object->user_completed == 1){
                    $user_completed = 1;
                }
            }

            if (empty($start_date_rating) && empty($end_date_rating) && $user_completed == 0){
                $rating_level_url = route('module.rating_level.course', [$course_id, 1, $row->id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
            }else{
                if ($start_date_rating){
                    if ($start_date_rating <= now()){
                        $check[] = true;
                    }else{
                        $check[] = false;
                    }
                }

                if ($end_date_rating){
                    if ($end_date_rating >= now()){
                        $check[] = true;
                    }else{
                        $check[] = false;
                    }
                }

                if ($user_completed == 1){
                    if ($user_result == 1){
                        $check[] = true;
                    }else{
                        $check[] = false;
                    }
                }

                if (!in_array(false, $check)){
                    $rating_level_url = route('module.rating_level.course', [$course_id, 1, $row->id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
                }
            }

            $user_rating_level_course = RatingLevelCourse::query()
                ->where('course_rating_level_id', '=', $row->id)
                ->where('user_id', '=', Auth::id())
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $course_id)
                ->where('course_type', '=', 1)
                ->first();
            if ($user_rating_level_course){
                $rating_status = $user_rating_level_course->send == 1 ? 1 : 2;
                $rating_level_url = route('module.rating_level.edit_course', [$course_id, 1, $row->id, $user_rating_level_course->rating_user]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
            }

            $row->course_name = $course->name;
            $row->course_time = get_date($course->start_date) . ($course->end_date ? ' đến '. get_date($course->end_date) : '');
            $row->rating_time = get_date($start_date_rating) . ($end_date_rating ? ' đến ' .get_date($end_date_rating) : '');
            $row->rating_status = $rating_status == 1 ? 'Đã đánh giá' : ($rating_status == 2 ? 'Chưa gửi đánh giá' : 'Chưa đánh giá');
            $row->rating_level_url = $rating_level_url;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getObject($course_id, Request $request)
    {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = OnlineCourse::query();
        $query->select([
            'a.title_join_id',
            'a.title_recommend_id',
        ]);

        $query->from('el_online_course AS a');
        $query->where('a.id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            dd($row->title_join_id);
        }

        json_result([
            'total' => $count,
            'rows' => $rows
        ]);
    }
}
