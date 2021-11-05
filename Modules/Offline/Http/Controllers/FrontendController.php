<?php

namespace Modules\Offline\Http\Controllers;

use App\Automail;
use App\Config;
use App\CourseBookmark;
use App\Models\Categories\Area;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\StudentCost;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\ProfileView;
use App\Scopes\DraftScope;
use App\Slider;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Indemnify\Entities\Indemnify;
use Modules\LogViewCourse\Entities\LogViewCourse;
use Modules\Notify\Entities\Notify;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRating;
use Modules\Offline\Entities\OfflineRatingLevel;
use Modules\Offline\Entities\OfflineRatingLevelObject;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineComment;
use Modules\Offline\Entities\OfflineCourseComplete;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineStudentCostByUser;
use Modules\PointHist\Entities\PointHist;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionShare;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Rating\Entities\RatingCourse;
use App\Profile;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\RefererHist\Entities\RefererRegisterCourse;
use Illuminate\Support\Facades\Crypt;
use Modules\User\Entities\TrainingProcess;
use App\EmulationPromotion;
use App\Models\Categories\Titles;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $items = $this->getItems($request);
        $text_status = function ($status) {
            return OfflineCourse::getStatusRegisterText($status);
        };
        $class_status = function ($status) {
            return OfflineCourse::getBtnClassStatusRegister($status);
        };
        $sliders = Slider::where('status', '=', 1)
            ->where('location', '=', 'offline')
            ->get();
        $check_bookmarks = function ($course_id, $course_type){
            return CourseBookmark::checkExist($course_id, $course_type);
        };

        return view('offline::frontend.index', [
            'items' => $items,
            'sliders' => $sliders,
            'text_status' => $text_status,
            'class_status' => $class_status,
            'check_bookmarks' => $check_bookmarks
        ]);
    }

    public function getItems(Request $request) {
        $search = $request->get('q');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');
        $training_program_id = $request->get('training_program_id');
        $subject_id = $request->get('subject_id');
        $status = $request->get('status');
        $user_id = Auth::id();
        $status = $request->get('status');

        $profile = Profile::find($user_id);
        $unit_user = Unit::getTreeParentUnit($profile->unit_code);

        $query = OfflineCourse::query();
        $query->select(['a.*']);
        $query->from('el_offline_course as a');
        if($status && $status !== 5) {
            $query->leftjoin('el_offline_register as b','b.course_id','=','a.id');
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

        $get_course_id_register = OfflineRegister::where('user_id',$user_id)->pluck('course_id')->toArray();
        $get_course_id_complete = OfflineCourseComplete::where('user_id',$user_id)->pluck('course_id')->toArray();

        if($status && $status == 1) {
            $query->whereNotIn('a.id', $get_course_id_register);
            $query->where(function ($sub){
                $sub->orWhere('end_date', '>', date('Y-m-d'));
            });
        } elseif($status && $status == 2) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',1);
        } elseif($status && $status == 3) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',2);
        } elseif($status && $status == 4) {
            $query->leftjoin('el_offline_course_complete as c','c.course_id','=','a.id');
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

        if ($subject_id) {
            $query->where('a.subject_id', '=', $subject_id);
        }

        $query->orderByDesc('a.id');
        $items = $query->paginate(20);
        $items->appends($request->query());

        return $items;
    }

    public function detail($id, Request $request){
        if ($request->share_key){
            $user_share = PromotionShare::query()
                ->where('course_id', '=', $id)
                ->where('type', '=', 2)
                ->where('share_key', '=', $request->share_key)
                ->first();

            if ($user_share->user_id != Auth::id()){
                $setting = PromotionCourseSetting::where('course_id', $id)
                    ->where('type', 2)
                    ->where('status', 1)
                    ->where('code', '=', 'share_course')
                    ->first();
                if ($setting && $setting->point){
                    $user_point = PromotionUserPoint::firstOrCreate([
                        'user_id' => $user_share->user_id
                    ], [
                        'point' => 0,
                        'level_id' => 0
                    ]);
                    $user_point->point += $setting->point;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_share->user_id);
                    $user_point->update();

                    $this->saveHistoryPromotion($user_share->user_id, $setting->point, $setting->course_id, $setting->id);
                    $this->savePromotionEmulation($user_share->user_id, $setting->point,$setting->course_id, 2, $setting->id);
                }
            }

            return redirect()->route('module.offline.detail', ['id' => $id]);
        }

        OfflineCourse::updateItemViews($id);
        $user_id = Auth::id();
        $item = OfflineCourse::where('id', '=', $id)
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->firstOrFail();

        $register = OfflineRegister::where('user_id', '=', $user_id)
            ->where('course_id', '=', $id)
            ->where('status', '=', 1)
            ->first();

        $categories = OfflineCourse::getCourseCategory($item->training_program_id, $item->id);

        $comments = OfflineComment::where('course_id', '=', $id)->get();
        $profile = Profile::where('user_id', '=', $user_id)->first();
        $rating_course = RatingCourse::where('course_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', 2)
            ->first();

        $indem = Indemnify::where('user_id', '=', $user_id)->where('course_id', '=', $id)->first();

        $sliders = Slider::where('status', '=', 1)
            ->where('location', '=', 'online')
            ->get();
        $rating_star = OfflineRating::where('course_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->first();
        $text_status = function ($status) {
            return OfflineCourse::getStatusRegisterText($status);
        };
        $class_status = function ($status) {
            return OfflineCourse::getBtnClassStatusRegister($status);
        };
        $course_time = $item->course_time;
        $course_time_unit = $item->course_time_unit;
        $this->updateLogViewCourse($item);

        $count_rating_level = $query = OfflineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($id){
                $sub->select(['offline_rating_level_id'])
                    ->from('el_offline_rating_level_object')
                    ->where('course_id', '=', $id)
                    ->where('object_type', '=', 1)
                    ->where(function ($sub2){
                        $sub2->orWhereNull('end_date');
                        $sub2->orWhere('end_date', '>=', now());
                    })
                    ->pluck('offline_rating_level_id')
                    ->toArray();
            })
            ->whereExists(function ($sub2) use ($id, $user_id){
                $sub2->select(['id'])
                    ->from('el_offline_register')
                    ->where('user_id', '=', $user_id)
                    ->where('course_id', '=', $id)
                    ->where('status', '=', 1);
            })
            ->where('course_id', '=', $id)
            ->whereNotExists(function ($sub) use ($user_id, $id){
                $sub->select(['id'])
                    ->from('el_rating_level_course as rlc')
                    ->whereColumn('rlc.course_rating_level_id', '=', 'el_offline_rating_level.id')
                    ->where('rlc.user_id', '=', $user_id)
                    ->where('rlc.user_type', '=', 1)
                    ->where('rlc.course_id', '=', $id)
                    ->where('rlc.course_type', '=', 2);
            })
            ->count();

        return view('offline::frontend.detail', [
            'item' => $item,
            'categories' => $categories,
            'comments' => $comments,
            'profile' => $profile,
            'rating_course' => $rating_course,
            'sliders' => $sliders,
            'rating_star' => $rating_star,
            'text_status' => $text_status,
            'class_status' => $class_status,
            'register' => $register,
            'course_time' => $course_time,
            'course_time_unit' => $course_time_unit,
            'indem' => $indem,
            'count_rating_level' => $count_rating_level
        ]);
    }

    public function updateLogViewCourse(OfflineCourse $course)
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
    public function registerCourse($id, Request $request)
    {
        $course = OfflineCourse::findOrFail($id);
        $user_id = Auth::id();
        $profile_user = ProfileView::where('user_id',\Auth::id())->first();

        $profile = Profile::whereUserId($user_id)->first();
        $area_by_unit = @$profile->unit;

        $referer = $request->referer;
        /* insert hist point  register referer*/
        if ($referer) {
            if (Profile::validRefer($referer)) {
                RefererRegisterCourse::saveReferRegisterOfflineCourse($id,$referer);
                PromotionUserPoint::updatePointRegisterCourse($referer);
                PointHist::savePointHist($referer);
            }else{
                json_message('Mã người giới thiệu không hợp lệ','error');
            }
        }

        !empty($course->title_join_id) ? $get_title_join_model_id = json_decode($course->title_join_id) : $get_title_join_model_id = [];
        !empty($course->title_recommend_id) ? $get_title_recommend_model_id = json_decode($course->title_recommend_id) : $get_title_recommend_model_id = [];

        $get_training_area_id = !empty($course->training_area_id) ? json_decode($course->training_area_id) : [];

        if( (in_array(0, $get_title_join_model_id) || in_array($profile_user->title_id, $get_title_join_model_id) || in_array(0, $get_title_recommend_model_id) || in_array($profile_user->title_id, $get_title_recommend_model_id)) && in_array(@$area_by_unit->area_id, $get_training_area_id) ) {

        }else{
            json_result([
                'status' => 'warning',
                'message' => 'Anh chị không thuộc đối tượng tham gia khóa học. Vui lòng liên hệ Trung tâm đào tạo',
            ]);
        }
        $model = OfflineRegister::firstOrCreate(['user_id'=>Auth::id(),'course_id'=>$id],[
            'unit_by'=>$profile_user->unit_id
        ]);
        if ($model) {
            //update training process
            $subject = Subject::findOrFail($course->subject_id);
            $profile = \DB::table('el_profile_view')->where('user_id','=',$user_id)->first();
            $title = Titles::where('id',$profile->title_id)->first();
            TrainingProcess::updateOrCreate(
                [
                    'user_id'=>$user_id,
                    'course_id'=>$id,
                    'course_type'=>2
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
                ]
            );
            ////
            $this->sendMailManagerApprove($id,$model->id);
            if (url_mobile()){
                json_result([
                    'status' => 'success',
                    'message' => 'Đăng ký thành công',
                    'redirect' => route('themes.mobile.frontend.offline.detail', [
                        'course_id' => $id
                    ])
                ]);
            }

            json_result([
                'status' => 'success',
                'message' => 'Đăng ký thành công',
                'redirect' => route('module.offline.detail', [
                    'id' => $id
                ])
            ]);
        }
    }
    public function sendMailManagerApprove($course_id,$register_id)
    {
        $user_id = Auth::id();
        $profile = Profile::where(['user_id'=>$user_id])->selectRaw("unit_id, concat(lastname,' ',firstname) as fname, unit_code")->firstOrFail();
        $full_name = $profile->fname;
        $course = OfflineCourse::find($course_id);
        $unit_id = $profile->unit_id;
        //truong don vi
        $user_managers = \DB::query()->from('el_unit_manager as a')->join('el_unit as b','a.unit_code','=','b.code')->join('el_profile as c','c.code','=','a.user_code')
            ->where(['b.id'=>$unit_id])->select('c.user_id')->get();
        foreach ($user_managers as $user){
            $signature = getMailSignature($user->user_id);
            $params = [
                'code' => $course->code,
                'course_name' => $course->name,
                'student' => $full_name,
                'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 2]),
                'signature' => $signature
            ];
            $this->execMailManagerApprove($params,[$user->user_id],$register_id);
        }
        //duoc phan quyen quan ly don vi
        $unit_id_arr = [];
        $unit = Unit::getTreeParentUnit($profile->unit_code);
        foreach ($unit as $item){
            $unit_id_arr[] = $item->id;
        }
        $users=\DB::query()
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
                    ->where('per.name', '=', 'offline-course-register-approve');
            })
            ->where(['e.name'=>'offline-course-register'])
            ->get();
        foreach ($users as $user){
            $signature = getMailSignature($user->user_id);
            $params = [
                'code' => $course->code,
                'course_name' => $course->name,
                'student' => $full_name,
                'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 2]),
                'signature' => $signature
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
        $automail->object_id = '2'.$register_id;
        $automail->object_type = 'approve_offline_register';
        $automail->addToAutomail();
    }
    public function comment($id, Request $request)
    {
        $this->validateRequest([
            'content' => 'required',
        ], $request, ['content' => 'Nội dung']);

        $model = new OfflineComment();
        $model->course_id = $id;
        $model->user_id = Auth::id();
        $model->content = $request->post('content');

        if ($model->save()) {
            json_result([
                'message' => 'Cảm ơn đã bình luận',
                'redirect' => route('module.online.detail', [
                    'id' => $id
                ])
            ]);
        }
    }

    public function rating($id, Request $request) {
        $this->validateRequest([
            'star' => 'required|min:1',
        ], $request, ['star' => 'Sao']);

        $user_id = Auth::id();
        if (OfflineRating::getRating($id, $user_id)) {
            json_message('Bạn đã đánh giá khóa học này', 'warning');
        }

        $model = new OfflineRating();
        $model->course_id = $id;
        $model->user_id = $user_id;
        $model->num_star = $request->star;

        if ($model->save()) {
            $setting = PromotionCourseSetting::where('course_id', $id)
                ->where('type', 2)
                ->where('status', 1)
                ->where('code', '=', 'rating_star')
                ->first();
            if ($setting && $setting->point){
                $user_point = PromotionUserPoint::firstOrCreate([
                    'user_id' => $user_id
                ], [
                    'point' => 0,
                    'level_id' => 0
                ]);
                $user_point->point += $setting->point;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_id);
                $user_point->update();

                $this->saveHistoryPromotion($user_id, $setting->point, $setting->course_id, $setting->id);
                $this->savePromotionEmulation($user_id, $setting->point,$setting->course_id, 2, $setting->id);
            }

            json_message('Cảm ơn bạn đã đánh giá');
        }

        json_message('Bạn đã đánh giá khóa học này', 'error');
    }

    public function search(Request $request)
    {
        $items = $this->getItems($request);
        $training_program = TrainingProgram::find($request->get('training_program_id'));
        $level_subject = LevelSubject::find($request->get('level_subject_id'));
        $subject = Subject::find($request->get('subject_id'));
        $status = $request->status;

        return view('offline::frontend.index', [
            'items' => $items,
            'training_program' => $training_program,
            'subject' => $subject,
            'level_subject' => $level_subject,
            'status' => $request->status,
        ]);
    }

    public function showModalQrcodeReferer(Request $request) {
        $course_id = $request->input('course_id');
        return view('online::modal.qrcode_referer' );
    }

    public function viewPDF($id,$key){
        $path = OfflineCourse::find($id)->getLinkViewPdf($id,$key);
        if (url_mobile()){
            $path = str_replace(config('app.url'), config('app.mobile_url'), $path);

            return view('themes.mobile.frontend.libraries.view_pdf', [
                'path' => $path,
            ]);
        }
        return view('offline::frontend.view_pdf', [
            'path' => $path,
        ]);
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
        $history->point = $point;
        $history->type = 2;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        $course_name = OfflineCourse::query()->find($course_id)->name;

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
        $history->type = 2;
        $history->course_id = $course_id;
        $history->course_setting_id = $setting;
        $history->save();
    }

    public function shareCourse($course_id, $type, Request $request){
        $promotion_share = PromotionShare::firstOrNew(['course_id' => $course_id, 'type' => $type, 'user_id' => Auth::id()]);
        $promotion_share->share_key = $request->share_key;
        $promotion_share->save();

        json_message('ok');
    }

    public function studentCost(Request $request){
        $student_costs = StudentCost::where('status','=',1)->get();
        return view('offline::frontend.student_cost',[
            'student_costs' => $student_costs,
        ]);
    }

    public function getDataCourse(Request $request) {
        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineCourse::query();
        $query->where('enter_student_cost', '=', 1);
        $query->where('end_date', '<', date('Y-m-d H:i:s'));
        $query->whereIn('id', function ($sub){
           $sub->select(['course_id'])
               ->from('el_offline_register')
               ->where('user_id', '=', Auth::id())
               ->pluck('course_id')
               ->toArray();
        });
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('code', 'like', '%'. $search .'%');
            });
        }
        if ($start_date) {
            $query->where('start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('start_date', '<=', date_convert($end_date, '23:59:59'));
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $register = OfflineRegister::whereCourseId($row->id)->where('user_id', '=', \Auth::id())->first();
            $register_cost = OfflineStudentCostByUser::where('register_id', '=', @$register->id)->where('course_id',$row->id)->get();
            $total_student_cost = OfflineStudentCostByUser::getTotalStudentCost(@$register->id, $row->id);

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            $student_costs = StudentCost::where('status','=',1)->get();
            foreach($student_costs as $key => $student_cost) {
                $check_register = (isset($register_cost[$key]) && $register_cost[$key]->manager_approved == 1) ? "readonly" : '';
                $count_register = count($register_cost) != 0 && isset($register_cost[$key]) ? number_format($register_cost[$key]->cost, 0) : '';

                $student_cost_row ='<input type="hidden" id="register_id_'. $student_cost->id .'_'. $row->id .'" name="regid" value="'. @$register->id .'">';
                $student_cost_row .='<input type="hidden" name="cost_id_'.$student_cost->id.'" value="'. $student_cost->id .'" id="cost_id_'.$student_cost->id.'_'. $row->id .'">';
                $student_cost_row .= '<input type="text" onchange="saveCost('.$student_cost->id.', '. $row->id .')"
                name="cost_'.$student_cost->id.'"
                value="'. $count_register .'"
                class="form-control is-number input_sudent_cost"
                id="input_sudent_cost_'.$student_cost->id.'_'. $row->id .'"
                autocomplete="off" '. $check_register .'>';

                $row->{'student_cost_'. $student_cost->id} = $student_cost_row;
            }

            $row->total_student_cost = number_format($total_student_cost, 0) . ' VNĐ';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getModalStudentCost(Request $request) {
        $this->validateRequest([
            'regid' => 'required',
        ], $request);
        $student_costs = StudentCost::where('status','=',1)->get();
        $register_cost = OfflineStudentCostByUser::where('register_id', '=', $request->regid)->get();
        $total_student_cost = OfflineStudentCostByUser::getTotalStudentCost($request->regid);
        return view('offline::modal.student_cost_by_user', [
            'regid' => $request->regid,
            'student_costs' => $student_costs,
            'register_cost' => $register_cost,
            'total_student_cost' => $total_student_cost
        ]);
    }

    public function saveStudentCost(Request $request){
        $register_id = $request->regid;
        $cost_id = $request->cost_id;
        $cost = str_replace(',','',$request->cost);
        $course_id = $request->course_id;
        // $notes = $request->note;
        if($cost > 0) {
            $model = OfflineStudentCostByUser::firstOrNew(['register_id' => $register_id, 'cost_id' => $cost_id, 'course_id' => $course_id]);
            $model->cost_id = $cost_id;
            $model->cost = (float) $cost;
            $model->register_id = $register_id;
            $model->course_id = $course_id;
            $model->save();
        }
        $total_student_cost = OfflineStudentCostByUser::getTotalStudentCost($register_id, $course_id);
        json_result([
            'status' => 'success',
            'message' => 'Thêm chi phí học viên thành công',
            'total_student_cost' => $total_student_cost,
        ]);
    }

    public function getDataRatingLevel($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $course = OfflineCourse::find($course_id);
        $user_id = Auth::id();

        $query = OfflineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($course_id){
                $sub->select(['offline_rating_level_id'])
                    ->from('el_offline_rating_level_object')
                    ->where('course_id', '=', $course_id)
                    ->where('object_type', '=', 1)
                    ->pluck('offline_rating_level_id')
                    ->toArray();
            })
            ->whereExists(function ($sub2) use ($course_id){
                $sub2->select(['id'])
                    ->from('el_offline_register')
                    ->where('user_id', '=', Auth::id())
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

            $rating_level_object = OfflineRatingLevelObject::query()
                ->where('course_id', '=', $course_id)
                ->where('offline_rating_level_id', '=', $row->id)
                ->where('object_type', '=', 1)
                ->first();
            if ($rating_level_object){
                $result = OfflineResult::query()
                    ->where('course_id', '=', $course_id)
                    ->where('user_id', '=', Auth::id())
                    ->where('result', '=', 1)
                    ->first();
                if ($result) {
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
                $rating_level_url = route('module.rating_level.course', [$course_id, 2, $row->id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
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
                    $rating_level_url = route('module.rating_level.course', [$course_id, 2, $row->id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
                }
            }

            $user_rating_level_course = RatingLevelCourse::query()
                ->where('course_rating_level_id', '=', $row->id)
                ->where('user_id', '=', Auth::id())
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $course_id)
                ->where('course_type', '=', 2)
                ->first();
            if ($user_rating_level_course){
                $rating_status = $user_rating_level_course->send == 1 ? 1 : 2;
                $rating_level_url = route('module.rating_level.edit_course', [$course_id, 2, $row->id, $user_rating_level_course->rating_user]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
            }

            $row->course_name = $course->name;
            $row->course_time = get_date($course->start_date) . ($course->end_date ? ' đến '. get_date($course->end_date) : '');
            $row->rating_time = get_date($start_date_rating) . ($end_date_rating ? ' đến ' .get_date($end_date_rating) : '');
            $row->rating_status = $rating_status == 1 ? 'Đã đánh giá' : ($rating_status == 2 ? 'Chưa gửi đánh giá' : 'Chưa đánh giá');
            $row->rating_level_url = $rating_level_url;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
