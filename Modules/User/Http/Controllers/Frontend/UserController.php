<?php

namespace Modules\User\Http\Controllers\Frontend;

use App\Config;
use App\CourseView;
use App\Models\Categories\LevelSubject;
use App\PermissionTypeUnit;
use App\PlanAppStatus;
use App\Profile;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Slider;
use App\UnitView;
use App\User;
use App\UserMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use Modules\ConvertTitles\Entities\ConvertTitles;
use Modules\ConvertTitles\Entities\ConvertTitlesRoadmap;
use Modules\Notify\Entities\Notify;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Online\Entities\OnlineRegister;
use Modules\Potential\Entities\Potential;
use Modules\Potential\Entities\PotentialRoadmap;
use Modules\Promotion\Entities\PromotionOrders;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;
use Modules\RefererHist\Entities\RefererHist;
use Modules\SubjectRegister\Entities\SubjectRegister;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\User\Entities\HistoryChangeInfo;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\UserCompletedSubject;
use Modules\CareerRoadmap\Entities\CareerRoadmapUser;
use App\Models\Categories\TrainingForm;
use Modules\Offline\Entities\OfflineRegisterView;
use Illuminate\Database\Query\Builder;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Discipline;
use App\ProfileView;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Promotion\Entities\PromotionLevel;
use App\Models\Categories\StudentCost;
use Jenssegers\Agent\Agent;

class UserController extends Controller
{
    public function index()
    {
        $user = Profile::whereUserId(\Auth::id())->first();
        $title = Titles::whereCode($user->title_code)->first();
        $unit = Unit::getTreeParentUnit($user->unit_code);
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        PromotionUserPoint::firstOrCreate(['user_id' => \Auth::id()], ['point' => 0, 'level_id' => 0]);
        $promotion = PromotionUserPoint::whereUserId(\Auth::id())->first();
        $promotion_level = '';
        if (!empty($promotion)) {
            $promotion_level = PromotionLevel::where('status', 1)->where('level', $promotion->level_id)->first();
        }
        $orders = PromotionOrders::whereUserId(\Auth::id())
            ->select('el_promotion_orders.*', 'el_promotion.name', 'el_promotion.images', 'el_promotion_group.name as group_name')
            ->join('el_promotion', 'promotion_id', 'el_promotion.id')
            ->join('el_promotion_group', 'el_promotion_group.id', 'promotion_group')
            ->paginate(8);
        $info_qrcode = json_encode(['user_id' => $user->user_id, 'type' => 'profile']);
        $sliders = Slider::where('status', '=', 1)
            ->where('type', '=', 1)
            ->where('location', '!=', 1)
            ->where(function ($sub) use ($unit) {
                $sub->whereNull('object');
                foreach ($unit as $item) {
                    $sub->orWhereIn('object', [$item->id]);
                }
            })
            ->get();
        $referer = \Request::segment(2) == 'referer' ? $user->referer : null;

        $career_roadmaps = CareerRoadmap::where('title_id', '=', @$title->id)
            ->where('primary', '=', 1)
            ->latest()->first();

        $user_meta = function ($key) {
            return UserMeta::where('user_id', '=', \Auth::id())->where('key', '=', $key)->first(['value']);
        };
        $user_name = User::find($user->user_id)->username;

        $training_by_title_category = TrainingByTitleCategory::where('title_id', '=', @$title->id)->get();
        $count_training_by_title_detail = TrainingByTitleDetail::where('title_id', '=', @$title->id)->count();
        $count_subject_completed = UserCompletedSubject::whereUserId($user->user_id)->groupBy(['subject_id'])->count();

        $profile = Profile::where('user_id', '=', \Auth::id())->first(['title_code']);
        $roadmaps = CareerRoadmap::where('title_id', '=', @$title->id)
            ->get(['id', 'name']);

        $roadmaps_user = CareerRoadmapUser::query()
            ->where('user_id', '=', Auth::id())
            ->where('title_id', '=', @$title->id)
            ->get(['id', 'name']);

        if ($user->date_title_appointment) {
            $start_date = $user->date_title_appointment;
        } elseif ($user->effective_date) {
            $start_date = $user->effective_date;
        } else {
            $start_date = $user->join_company;
        }

        $student_costs = StudentCost::where('status', '=', 1)->get();
        $agent = new Agent();
        return view('user::frontend.index', [
            'user' => $user,
            'title' => $title,
            'unit' => $unit,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'promotion' => $promotion,
            'orders' => $orders,
            'info_qrcode' => $info_qrcode,
            'sliders' => $sliders,
            'referer' => $referer,
            'career_roadmaps' => $career_roadmaps,
            'user_meta' => $user_meta,
            'user_name' => $user_name,
            'count_training_by_title_detail' => $count_training_by_title_detail,
            'count_subject_completed' => $count_subject_completed,
            'training_by_title_category' => $training_by_title_category,
            'roadmaps' => $roadmaps,
            'career_roadmaps' => $career_roadmaps,
            'roadmaps_user' => $roadmaps_user,
            'start_date' => $start_date,
            'promotion_level' => $promotion_level,
            'student_costs' => $student_costs,
            'agent' => $agent
        ]);
    }

    public function getDataPointHist(Request $request)
    {
        /*$user_id = \Auth::id();
        $query = \DB::query();
        $query->select([
            'a.*',
        ]);
        $query->from("el_point_hist AS a");
        $query->where('a.referer','=', $user_id);

        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_at = get_date($row->created_at,'d/m/Y h:i:s ');
        }*/
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = PromotionUserHistory::query()
            ->select(['a.*', 'b.code', 'c.name as video_name', 'd.name as promotion_name'])
            ->from('el_promotion_user_point_get_history as a')
            ->leftJoin('el_promotion_course_setting as b', 'b.id', '=', 'a.promotion_course_setting_id')
            ->leftJoin('el_daily_training_video as c', 'c.id', '=', 'a.video_id')
            ->leftJoin('el_promotion as d', 'd.id', '=', 'a.promotion')
            ->where('a.user_id', '=', Auth::id())
            ->where('a.point', '>', '0');

        $count = $query->count();
        $query->orderBy('a.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        $arr_object = [
            '1' => 'khóa học trực tuyến',
            '2' => 'khóa học tập trung',
            '3' => 'thi',
            '4' => 'khảo sát',
        ];

        foreach ($rows as $row) {
            if ($row->daily_training == 1) {
                $row->content = 'Học liệu đào tạo video: ' . $row->video_name;
            } else if ($row->donate_point == 1) {
                $row->content = 'Tặng điểm';
            } else if ($row->promotion) {
                $row->content = 'Quà tặng quy đổi ' . $row->promotion_name;
            } else {
                switch ($row->code) {
                    case 'complete':
                        $content = 'Hoàn thành ' . $arr_object[$row->type];
                        break;
                    case 'landmarks':
                        $content = 'Đạt mốc điểm trong ' . $arr_object[$row->type];
                        break;
                    case 'assessment_after_course':
                        $content = 'Thực hiện đánh giá sau khóa học của ' . $arr_object[$row->type];
                        break;
                    case 'evaluate_training_effectiveness':
                        $content = 'Đánh giá hiệu quả sau đào tạo của ' . $arr_object[$row->type];
                        break;
                    case 'rating_star':
                        $content = 'Đánh giá sao ' . $arr_object[$row->type];
                        break;
                    case 'share_course':
                        $content = 'Share ' . $arr_object[$row->type];
                        break;
                    case 'attendance':
                        $content = 'Tham gia ' . $arr_object[$row->type];
                        break;
                    default:
                        $content = '';
                        break;
                }
                $row->content = $content;
            }

            $row->createdate = $row->created_at->format('d/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function showModalReferer(Request $request)
    {
        $schedule_id = $request->input('schedule_id');
        $course_id = $request->input('course_id');
        return view('user::frontend.referer.qrcode');
    }

    public function getRefererHist(Request $request)
    {
        $user_id = \Auth::id();
        $query = \DB::query();
        $id_code = Profile::where('user_id', '=', $user_id)->value('id_code');
        $query->select([
            'a.*',
            'b.full_name as name_referer'
        ]);
        $query->from("el_referer_hist AS a");
        $query->leftJoin('el_profile_view as b', 'a.user_id', '=', 'b.user_id');
        $query->where('a.referer', '=', $id_code);

        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_at = get_date($row->created_at, 'd/m/Y h:i:s ');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveReferer(Request $request)
    {
        $this->validateRequest([
            'referer' => 'nullable|min:6|max:10',
        ], $request, Profile::getAttributeName());
        if (!Profile::validRefer($request->referer)) {
            json_message('Mã giới thiệu không hợp lệ', 'error');
        }
        $id = \Auth::id();
        $model = Profile::firstOrCreate(['user_id' => $id]);
        if ($model->referer) {
            json_message('Cập nhật thành công');
        } else
            $model->referer = $request->referer;
        if ($model->save()) {
            PromotionUserPoint::updatePointReferer($request->referer);
        }
        json_result(['message' => 'Cập nhật thành công', 'status' => 'success', 'redirect' => route('frontend.user.referer')]);
    }

    public function getDataRoadmap(Request $request)
    {
        $user_id = Auth::id();
        $user = \DB::table('el_profile_view')->where(['user_id' => $user_id])->first();
        $subQuery = \DB::table('el_training_process')
            ->where('titles_code', '=', $user->title_code)
            ->groupBy('subject_id')
            ->select([
                \DB::raw('MAX(id) as id'),
                'subject_id',
            ]);

        $query = \DB::query();
        $query->select([
            /*'c.id',
            'c.subject_id',
            'c.subject_code',
            'c.subject_name',
            'c.process_type',
            'c.start_date',
            'c.end_date',
            'c.time_complete',
            'c.mark',
            'c.pass',
            'c.note'*/
            'a.subject_id',
            'a.completion_time',
            'a.training_form',
            'd.code as training_program_code',
            'd.name as training_program_name',
            'subject.id',
            'subject.code as subject_code',
            'subject.name as subject_name',
        ]);
        $query->from("el_trainingroadmap AS a");
        /* $query->joinSub($subQuery,'b', function ($join){
            $join->on('b.subject_id', '=', 'a.subject_id');
        });
        $query->leftJoin('el_training_process as c', function ($join){
            $join->on('c.id', '=', 'b.id');
        });*/
        $query->leftJoin('el_subject as subject', 'subject.id', '=', 'a.subject_id');
        $query->leftJoin('el_training_program as d', 'd.id', '=', 'a.training_program_id');
        $query->where('a.title_id', '=', $user->title_id);
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $training_process = TrainingProcess::whereSubjectId($row->subject_id)
                ->where('titles_code', '=', $user->title_code)
                ->where('user_id', '=', $user_id)
                ->where('pass', 1)
                ->orderByDesc('updated_at')
                ->first();

            $hasCourse = $this->checkCourseSubject($row->subject_id);
            $row->start_date = $training_process ? get_date($training_process->start_date) : '';
            $row->end_date = $training_process ? get_date($training_process->end_date) : '';
            $row->score = ($training_process && $training_process->mark) ? number_format($training_process->mark, 2, ',', '.') : '';
            if ($row->training_program_code) {
                $btn = $hasCourse ? '<button class="btn btn-primary load-modal" data-url="' . route('module.frontend.user.show_modal_roadmap', [$row->subject_id]) . '">Đăng ký</button>' : '<button data-subject_id="' . $row->subject_id . '" class="btn btn-primary btnRegisterSubject">Đăng ký</button>';

                $row->result = ($training_process && $training_process->pass == 1) ? trans('backend.finish') : trans('backend.incomplete') . '<br/>' . $btn;
            }

            if ($row->completion_time && $training_process && $training_process->time_complete) {
                if ($training_process->course_type == 1) {
                    $row->start_effect = get_date($training_process->time_complete);
                    $end = strtotime(date("Y-m-d", strtotime($training_process->time_complete)) . " +{$row->completion_time} day");
                    $row->end_effect = strftime("%d/%m/%Y", $end);
                } else {
                    $row->start_effect = get_date($training_process->end_date);
                    $end = strtotime(date("Y-m-d", strtotime($training_process->end_date)) . " +{$row->completion_time} day");
                    $row->end_effect = strftime("%d/%m/%Y", $end);
                }
            } else {
                $row->start_effect = '-';
                $row->end_effect = '-';
            }

            $row->status = $training_process && $training_process->pass == 1 ? trans('backend.finish') : trans('backend.incomplete');
            $row->note = $training_process ? $training_process->note : '';
            if ($training_process) {
                if ($training_process->process_type == 2)
                    $row->process_type = trans('backend.subject_complete');
                elseif ($training_process->process_type == 4)
                    $row->process_type = trans('backend.merge_subject');
                elseif ($training_process->process_type == 5)
                    $row->process_type = trans('backend.split_subject');
                else
                    $row->process_type = '-';
            } else
                $row->process_type = '-';

            $training_form = $row->training_form ? json_decode($row->training_form, true) : [];
            if (in_array(1, $training_form) && !in_array(2, $training_form)) {
                $row->training_form = 'Online';
            } else if (!in_array(1, $training_form) && in_array(2, $training_form)) {
                $row->training_form = 'Tập trung';
            } else {
                $row->training_form = 'Online, Tập trung';
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function checkCourseSubject($subject_id)
    {
        $courses_online = OnlineCourse::where(['subject_id' => $subject_id, 'status' => 1, 'isopen' => 1])->exists();
        if ($courses_online)
            return true;
        $courses_offline = OfflineCourse::where(['subject_id' => $subject_id, 'status' => 1, 'isopen' => 1])->exists();
        if ($courses_offline)
            return true;
        return false;
    }
    public function getModalContent(Request $request)
    {
        $this->validateRequest([
            'roadmap_id' => 'required',
        ], $request);

        $user_new_recruitment = NewRecruitment::query()
            ->where('user_id', '=', \Auth::id())
            ->where('end_date', '>', date('Y-m-d H:i:s'))
            ->first();

        $user_convert_titles = ConvertTitles::query()
            ->where('user_id', '=', \Auth::id())
            ->where('end_date', '>', date('Y-m-d H:i:s'))
            ->first();

        $user_potential = Potential::query()
            ->where('user_id', '=', \Auth::id())
            ->where('end_date', '>', date('Y-m-d H:i:s'))
            ->first();

        if ($user_new_recruitment)
            $roadmap = NewRecruitmentRoadmap::find($request->roadmap_id);
        elseif ($user_convert_titles)
            $roadmap = ConvertTitlesRoadmap::find($request->roadmap_id);
        elseif ($user_potential)
            $roadmap = PotentialRoadmap::find($request->roadmap_id);
        else
            $roadmap = TrainingRoadmap::find($request->roadmap_id);

        $subject = Subject::find($roadmap->subject_id);

        return view('user::frontend.roadmap.modal_content', [
            'roadmap' => $roadmap,
            'subject' => $subject,
        ]);
    }

    public function getDataTrainingProcess()
    {
        $query = \DB::query();
        $query->select([
            'mark',
            'id',
            'course_id',
            'course_code',
            'course_name',
            'titles_name',
            'course_type',
            'process_type',
            'pass as result',
            'start_date',
            'end_date',
            'certificate',
        ]);
        $query->from('el_training_process');
        $query->where('user_id', '=', \Auth::id());
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->course_type == 1) {
                $course = OnlineCourse::find($row->course_id);
            } else {
                $course = OfflineCourse::find($row->course_id);
            }

            $row->image_cert = '';
            if (isset($course->cert_code) && $row->result == 1) {
                $row->image_cert = route('module.frontend.user.trainingprocess.certificate', ['course_id' => $row->course_id, 'course_type' => $row->course_type, 'user_id' => \Auth::id()]);
            }

            $row->training_form = '-';
            if ($course) {
                $training_form = TrainingForm::where('id', $course->training_form_id)->first();
                $row->training_form = @$training_form->name;
            }

            $row->course_type = $row->course_type == 1 ? trans('backend.onlines') : trans('backend.offline');
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->score = $row->mark ? number_format($row->mark, 2, ',', '.') : '';

            if ($row->process_type == 2)
                $row->process_type = trans('backend.subject_complete');
            elseif ($row->process_type == 4)
                $row->process_type = trans('backend.merge_subject');
            elseif ($row->process_type == 5)
                $row->process_type = trans('backend.split_subject');
            else
                $row->process_type = '-';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataQuizResult()
    {
        $query = \DB::query()
            ->select([
                'a.quiz_id',
                'c.id',
                'c.code',
                'c.name',
                'c.limit_time',
                'b.start_date',
                'b.end_date',
                'd.grade',
                'd.result',
                'd.reexamine'
            ])
            ->from('el_quiz_register as a')
            ->join('el_quiz_part as b', 'b.id', '=', 'a.part_id')
            ->join('el_quiz as c', 'c.id', '=', 'b.quiz_id')
            ->leftJoin('el_quiz_result as d', function ($join) {
                $join->on('a.user_id', '=', 'd.user_id');
                $join->on('d.quiz_id', '=', 'a.quiz_id');
            })
            ->where('a.user_id', '=', \Auth::id());
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date, 'd/m/Y H:i');
            $row->end_date = get_date($row->end_date, 'd/m/Y H:i');
            $row->grade = number_format(($row->reexamine ? $row->reexamine : $row->grade), 2, ',', '.');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function changeAvatar(Request $request)
    {
        $posts = ['selectavatar' => $request->file('selectavatar')];
        $rules = ['selectavatar' => 'required|image|max:10240'];
        $message = [
            'selectavatar.required' => 'Chưa chọn hình để upload',
            'selectavatar.image' => 'File hình không hợp lệ',
            'selectavatar.uploaded' => 'Dung lượng hình không được lớn hơn 10mb'
        ];

        $validator = \Validator::make($posts, $rules, $message);
        if ($validator->fails()) {
            return redirect()->back();
        }

        $avatar = $request->file('selectavatar');
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $extension = $avatar->getClientOriginalExtension();
        $filename = date('Y/m/d') . '/avatar-' . \Auth::id() . '.' . $extension;

        if ($storage->putFileAs('profile', $avatar, $filename)) {
            $profile = Profile::find(\Auth::id());
            $model = HistoryChangeInfo::firstOrNew(['user_id' => \Auth::id(), 'key' => 'avatar']);
            $model->user_id = \Auth::id();
            $model->key = 'avatar';
            $model->value_old = $profile->avatar;
            $model->value_new = $filename;
            $model->status = 1;
            $model->save();
            $profile->avatar = $filename;
            $profile->save();

            json_result([
                'status' => 'sucess',
                'message' => 'Đã thay đổi ảnh đại diện',
                'redirect' => route('module.frontend.user.info'),
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function changePass(Request $request)
    {
        $this->validateRequest([
            'password_old' => 'required|min:8|max:32',
            'password' => 'required|min:8|max:32',
            'repassword' => 'same:password',
        ], $request, Profile::getAttributeName());
        $password_old = $request->password_old;

        $user = User::find(\Auth::id());
        if ($user) {
            $hash = $user->password;
            if (password_verify($password_old, $hash)) {
                $user->password = password_hash($request->password, PASSWORD_DEFAULT);
                $user->save();
                json_result([
                    'status' => 'success',
                    'message' => 'Đổi mật khẩu thành công',
                    'redirect' => route('login'),
                ]);
            } else {
                json_result(['status' => 'error', 'message' => 'Mật khẩu cũ không đúng']);
            }
        }
        return redirect(route('login'));
    }

    public function changeUserInfo(Request $request)
    {
        $this->validateRequest([
            'key' => 'required',
            'value_new' => 'required',
        ], $request, HistoryChangeInfo::getAttributeName());

        $key = $request->key;
        $value_old = $request->value_old;
        $value_new = $request->value_new;
        $note = $request->note;

        $model = HistoryChangeInfo::firstOrNew(['user_id' => \Auth::id(), 'key' => $key]);
        $model->user_id = \Auth::id();
        $model->key = $key;
        $model->value_old = $value_old ? $value_old : null;
        $model->value_new = $value_new;
        $model->note = $note ? $note : null;
        $model->status = 2;
        $model->save();

        $unit_id = [];
        $unit = Unit::getTreeParentUnit(Profile::getUnitCode());
        foreach ($unit as $item) {
            $unit_id[] = $item->id;
        }

        $query = \DB::query()
            ->from('el_user_permission_type as a')
            ->leftJoin('el_permission_type_unit as b', 'b.permission_type_id', '=', 'a.permission_type_id')
            ->leftJoin('el_permissions as c', 'c.id', '=', 'a.permission_id')
            ->where(function ($sub) use ($unit_id) {
                $sub->orWhere(function ($sub1) use ($unit_id) {
                    $sub1->where('b.type', '=', 'group-child')
                        ->whereIn('b.unit_id', $unit_id);
                });
                $sub->orWhere(function ($sub2) {
                    $sub2->where('b.type', '=', 'owner')
                        ->where('b.unit_id', '=', Profile::getUnitId());
                });
            })
            ->whereIn('c.name', function ($sub2) {
                $sub2->select(['per.parent'])
                    ->from('el_model_has_permissions as model')
                    ->leftJoin('el_permissions as per', 'per.id', '=', 'model.permission_id')
                    ->whereColumn('model.model_id', '=', 'a.user_id')
                    ->where('per.name', '=', 'user-approve-change-info');
            })
            ->where('c.name', '=', 'user')
            ->pluck('a.user_id')->toArray();

        $user_managers = $query;
        if (count($user_managers) > 0) {
            foreach ($user_managers as $user) {
                $model = new Notify();
                $model->user_id = $user;
                $model->subject = 'Duyệt thay đổi thông tin';
                $model->content = 'Nhân viên ' . Profile::fullname(\Auth::id()) . ' vừa thay đổi thông tin. Vui lòng vào quản trị để duyệt thông tin thay đổi';
                $model->url = '';
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
                $notification->add($user);
            }
            $notification->save();
        }

        if (url_mobile()) {
            $redirect = route('themes.mobile.frontend.profile');
        } else {
            $redirect = route('module.frontend.user.info');
        }

        json_result([
            'status' => 'sucess',
            'message' => 'Thông tin đã thay đổi. Xin chờ duyệt...!',
            'redirect' => $redirect,
        ]);
    }

    public function showPlanSuggest()
    {
        $user = Profile::where('user_id', \Auth::id())->first();
        $title = Titles::where('code', '=', $user->title_code)->first();
        return view('user::frontend.plansuggest.index', [
            'user' => $user,
            'title' => $title,
        ]);
    }

    public function getDataPlanSuggest(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $unit_code = Profile::where('user_id', '=', \Auth::id())->value('unit_code');
        $query = PlanSuggest::query()->where('unit_code', '=', $unit_code);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        //        foreach ($rows as $row) {
        //            $row->start_date = get_date($row->start_date,'d/m/Y H:i');
        //            $row->end_date = get_date($row->end_date,'d/m/Y H:i');
        //            $row->grade = number_format($row->grade,2,',','.');
        //        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function createFormPlanSuggest(Request $request)
    {
        $data = array();
        $subject = Subject::where('status', '=', 1)->get();
        $title = Titles::where('status', '=', 1)->get();
        $data['subject'] = $subject;
        $data['title'] = $title;
        if ($request->id) {
            $id = (int) $request->id;
            $planSuggest = PlanSuggest::find($id);
            $data['planSuggest'] = $planSuggest;
            $data['subject_select'] = $planSuggest->subject_name;
            $data['title_select'] = $planSuggest->title ? array_values(json_decode($planSuggest->title, true)) : [];
        }
        json_result($data);
    }

    public function savePlanSuggest(Request $request)
    {
        $model = PlanSuggest::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->intend = date('Y-m-d', strtotime('01-' . str_replace('/', '-', $request->intend)));
        $model->unit_code = Profile::where('user_id', '=', \Auth::id())->value('unit_code');
        $model->created_by = \Auth::id();
        if ($model->save()) {
            /************************************/
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save')
            ]);
        }
    }

    public function certificate($course_id, $course_type, $user_id)
    {
        $query = \DB::query();
        $query->select([
            'b.end_date',
            'a.score',
            'b.cert_code',
            'c.created_at as date_complete',
        ]);
        $query->from('el_course_register_view AS a');
        $query->join('el_course_view AS b', function ($join) {
            $join->on('a.course_id', '=', 'b.course_id');
            $join->on('a.course_type', '=', 'b.course_type');
        });
        $query->leftJoin('el_course_complete AS c', function ($join) {
            $join->on('c.course_id', '=', 'b.course_id');
            $join->on('c.course_type', '=', 'b.course_type');
        });
        $query->where('a.user_id', '=', \Auth::id());
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.course_type', '=', $course_type);

        $model = $query->first();

        $profile = Profile::find($user_id);
        $unit = @$profile->unit->name;
        $title = @$profile->titles->name;
        $fullname = $profile->lastname . ' ' . $profile->firstname;
        //$fullname = mb_convert_case($fullname, MB_CASE_UPPER, "UTF-8");

        $day = get_date(@$model->date_complete, 'd');
        $month = get_date(@$model->date_complete, 'm');
        $year = get_date(@$model->date_complete, 'Y');

        if ($course_type == 1) {
            $course = OnlineCourse::find($course_id);
        } else {
            $course = OfflineCourse::find($course_id);
        }
        $certificate = \Modules\Certificate\Entities\Certificate::find($course->cert_code);
        $course_name = $course->name;

        $storage = \Storage::disk('upload');
        $path = $storage->path($certificate->image);
        $temp = str_replace($certificate->image, str_replace('.', '_' . $course_id . '.', $certificate->image), $path);

        $image = ImageManagerStatic::make($path);

        $image->text($fullname, 500, 520, function ($font) {
            $font->file(public_path('fonts/UTM Wedding K&T.ttf'));
            $font->size(100);
            $font->color('#bd8e34');
        });

        /*$image->text($unit, 710, 1630, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(50);
        });*/

        $image->text($title, 870, 655, function ($font) {
            $font->file(public_path('fonts/FiraSansExtraCondensed-Regular.ttf'));
            $font->size(50);
            $font->align('center');
        });

        $center_x    = 870;
        $center_y    = 830;
        $max_len     = 100;
        $font_height = 20;

        $lines = explode("/n", wordwrap($course_name, $max_len, "/n", true));
        $y     = $center_y - ((count($lines) - 1) * $font_height);
        foreach ($lines as $line) {
            $line = Str::upper($line);

            $image->text($line, $center_x, $y, function ($font) {
                $font->file(public_path('fonts/FiraSansExtraCondensed-Bold.ttf'));
                $font->size(60);
                $font->align('center');
                $font->color('#E53336');
            });
            $y += $font_height * 2;
        }

        /*$image->text($day, 1535, 2325, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
        });

        $image->text($month, 1725, 2325, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
        });

        $image->text($year, 1895, 2325, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
        });*/

        $image->save($temp);

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($temp, 'chung_chi_' . Str::slug($fullname, '_') . '.png', $headers);

        //return \Storage::download($temp);
    }

    public function getMyCourse($type, Request $request)
    {
        $user_id = getUserId();
        $user_type = getUserType();

        $search = $request->get('q');
        $type_course = $request->get('type_course');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $status = $request->get('status');

        $query = \DB::table('el_course_view as a')
            ->select([
                'a.*',
            ])
            ->join('el_course_register_view as b', function ($join) {
                $join->on('a.course_id', '=', 'b.course_id');
                $join->on('a.course_type', '=', 'b.course_type');
            })

            ->where('b.user_id', '=', $user_id)
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.isopen', '=', 1);

        if ($type == 1 || $type_course == 1) {
            $query->where('b.course_type', '=', 1);
            $query->where('a.course_type', '=', 1);
        } else if ($type == 2 || $type_course == 2) {
            $query->where('b.course_type', '=', 2);
            $query->where('a.course_type', '=', 2);
        }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('a.code', 'like', '%' . $search . '%');
                $subquery->orWhere('a.name', 'like', '%' . $search . '%');
            });
        }
        if ($start_date) {
            $query->where('a.start_date', '>=', date_convert($start_date, '00:00:00'));
        }
        if ($end_date) {
            $query->where('a.end_date', '<=', date_convert($end_date, '23:59:59'));
        }
        $query->orderBy('a.id', 'desc');

        $count = $query->count();
        $rows = $query->paginate(12);
        foreach ($rows as $row) {
            $now = date('Y-m-d');
            $row->avg_rating_star = 0; //@OnlineCourse::find($row->id)->avgRatingStar();
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->course_url = $row->course_type == 1 ? route('module.online.detail', ['id' => $row->course_id]) : route('module.offline.detail', ['id' => $row->course_id]);

            $row->course_time_unit = preg_replace("/[^a-z]/", '', $row->course_time);
            $row->course_time = preg_replace("/[^0-9]./", '', $row->course_time);
        }

        if ($user_type == 1) {
            $user = Profile::whereUserId(\Auth::id())->first();
            $unit = Unit::getTreeParentUnit(@$user->unit_code);
            $title = Titles::whereCode(@$user->title_code)->first();

            PromotionUserPoint::firstOrCreate(['user_id' => auth()->id()], ['point' => 0, 'level_id' => 0]);
            $promotion = PromotionUserPoint::whereUserId(auth()->id())
                ->select('el_promotion_user_point.*', 'el_promotion_level.level', 'el_promotion_level.images', 'el_promotion_level.name')
                ->join('el_promotion_level', 'el_promotion_user_point.level_id', 'level')
                ->first();
            $sliders = Slider::where('status', '=', 1)->where('type', '=', 1)->where('location', '!=', 1)
                ->where(function ($sub) use ($unit) {
                    $sub->whereNull('object');
                    foreach ($unit as $item) {
                        $sub->orWhereIn('object', [$item->id]);
                    }
                })->get();
            $career_roadmaps = CareerRoadmap::where('title_id', '=', @$title->id)
                ->where('primary', '=', 1)
                ->latest()->first();
        } else {
            $promotion = '';
            $sliders = '';
            $career_roadmaps = '';
        }

        $agent = new Agent();
        return view('user::frontend.index', [
            'total' => $count,
            'items' => $rows,
            'promotion' => $promotion,
            'sliders' => $sliders,
            'career_roadmaps' => $career_roadmaps,
            'type' => $type,
            'user_type' => $user_type,
            'agent' => $agent
        ]);
    }

    public function getData(Request $request)
    {
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = \DB::table('el_course_view as a')
            ->join('el_course_register_view as b', function ($join) {
                $join->on('a.course_id', '=', 'b.course_id');
                $join->on('a.course_type', '=', 'b.course_type');
            })
            ->where('b.user_id', '=', Auth::id())
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.isopen', '=', 1)
            ->select(['a.*']);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $now = date('Y-m-d');
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->course_url = $row->course_type == 1 ? route('module.online.detail', ['course_id' => $row->id]) : route('module.offline.detail', ['course_id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    protected function calDate($date1, $date2)
    {
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        $total_date = $years * 365 + $months * 30 + $days;

        return number_format($total_date, 2);
    }

    private function isEvaluation($start_evaluation, $status)
    {
        $days = (strtotime(date('Y-m-d')) - strtotime($start_evaluation)) / (60 * 60 * 24);

        if (!$start_evaluation || $days < 0 || $status <> 2)
            return 0; // chưa tới hạn đánh giá
        if ($days >= 0 && $days <= 8)
            return 1; // đánh giá
        if ($days > 8)
            return 2; // hết hạn đánh giá
        return 0;
    }

    public function getPromotionHistory(Request $request)
    {
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = PromotionUserHistory::whereUserId(auth()->id())
            ->select('el_promotion_user_point_get_history.*', 'el_online_course.name')
            ->join('el_online_course', 'el_promotion_user_point_get_history.course_id', 'el_online_course.id');

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->createdat = $row->created_at->format('d-m-Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function myCapabilities()
    {
        $user = Profile::findOrFail(\Auth::id());
        $unit = Unit::where('code', '=', $user->unit_code)->first();
        $title = Titles::where('code', '=', $user->title_code)->first();

        return view('user::frontend.capabilities.course', [
            'user' => $user,
            'unit' => $unit,
            'title' => $title,
        ]);
    }

    public function showModalRoadmap(Request $request)
    {
        $subject_id = $request->subject;
        $subject = Subject::find($subject_id);
        if (url_mobile()) {
            return view('trainingbytitle::mobile.modal_register_roadmap', [
                'subject' => $subject,
                'subject_id' => $subject_id,
            ]);
        }

        return view('user::frontend.roadmap.modal_register_roadmap', [
            'subject' => $subject,
            'subject_id' => $subject_id,
        ]);
    }

    public function getCourseBySubject(Request $request)
    {
        $subject_id = $request->subject;
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $courses_offline = OfflineCourse::where(['subject_id' => $subject_id, 'status' => 1, 'isopen' => 1])
            ->select('id', 'code', 'name', \DB::raw('2 as course_type'), 'start_date', 'end_date');
        $courses_online = OnlineCourse::where(['subject_id' => $subject_id, 'status' => 1, 'isopen' => 1])
            ->select('id', 'code', 'name', \DB::raw('1 as course_type'), 'start_date', 'end_date');

        $query = $courses_online->union($courses_offline);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->type = $row->course_type == 1  ? 'Online' : trans('backend.offline');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function registerRoadmap(Request $request)
    {
        $course_id = $request->course_id;
        $course_type = $request->course_type;
        $subject_id = $request->subject_id;
        $user_id = Auth::id();
        $error = false;
        if (!$course_id) {
            $exists = SubjectRegister::where(['user_id' => $user_id, 'subject_id' => $subject_id])->exists();
            if ($exists)
                $error = true;
            else {
                $model = SubjectRegister::firstOrNew(['user_id' => $user_id, 'subject_id' => $subject_id]);
                $model->status = 1;
                $model->note = 'Ghi danh từ tháp đào tạo';
                $model->save();
            }
        } else {
            if ($course_type == 1) {
                if (OnlineRegister::where(['user_id' => $user_id, 'course_id' => $course_id])->exists())
                    $error = true;
                else {
                    $model = OnlineRegister::firstOrNew(['user_id' => $user_id, 'course_id' => $course_id]);
                    $model->status = 1;
                    $model->note = 'Ghi danh từ tháp đào tạo';
                    $model->save();
                }
            } elseif ($course_type == 2) {
                if (OfflineRegister::where(['user_id' => $user_id, 'course_id' => $course_id])->exists())
                    $error = true;
                else {
                    $model = OfflineRegister::firstOrNew(['user_id' => $user_id, 'course_id' => $course_id]);
                    $model->status = 1;
                    $model->note = 'Ghi danh từ tháp đào tạo';
                    $model->save();
                }
            }
        }
        if ($error)
            json_result([
                'status' => 'error',
                'message' => 'Bạn đã ghi danh khóa học này rồi!'
            ]);
        json_result([
            'status' => 'success',
            'message' => 'Ghi danh thành công'
        ]);
    }

    public function getSubjectRegister(Request $request)
    {
        $user_id = \Auth::id();
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        //            Profile::addGlobalScope(new DraftScope('user_id'));
        $prefix = \DB::getTablePrefix();
        $query = SubjectRegister::query();
        $query->select('el_subject_register.*', \DB::raw("concat(" . $prefix . "b.lastname,' '," . $prefix . "b.firstname) as full_name"), 'c.name as subject', 'c.code');
        $query->from('el_subject_register')->join('el_profile as b', 'el_subject_register.user_id', 'b.user_id')
            ->join('el_subject as c', 'el_subject_register.subject_id', 'c.id')
            ->where('el_subject_register.user_id', '=', $user_id);
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('c.name', 'like', '%' . $search . '%');
                $sub_query->orWhere('c.code', 'like', '%' . $search . '%');
            });
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_date = get_date($row->created_at, 'd/m/Y H:i:s');
            $row->status_name = $row->status == 1 ? 'Đã đăng ký' : 'Hủy đăng ký';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function updateSubjectRegister(Request $request)
    {
        $model = SubjectRegister::findOrFail($request->id);
        $model->status = 2;
        $model->save();
        json_message('Hủy thành công');
    }

    public function getChildTrainingByTitleCategory(Request $request)
    {
        $cate_id = $request->id;
        $start_date = $request->start_date;

        // $profile = Profile::whereUserId(Auth::id())->first();
        // if ($profile->date_title_appointment){
        //     $start_date = $profile->date_title_appointment;
        // }elseif ($profile->effective_date){
        //     $start_date = $profile->effective_date;
        // }else{
        //     $start_date = $profile->join_company;
        // }
        // dd($start_date);
        $childs = TrainingByTitleDetail::where('training_title_category_id', '=', $cate_id)->get();

        $level_subject_arr = [];
        foreach ($childs as $child) {
            $subject = Subject::find($child->subject_id);
            $level_subject = LevelSubject::find($subject->level_subject_id);
            $level_subject_arr[$level_subject->id] = @$level_subject->name;
            $end_date = Carbon::parse($start_date)->addDays($child->num_date)->format('d/m/Y');

            $child->level_subject = @$level_subject->id;
            $child->start_date = get_date($start_date);
            $child->end_date = $end_date;

            $count_course_by_subject = CourseView::whereSubjectId($child->subject_id)->whereStatus(1)->count();
            $count_course_completed_by_subject = UserCompletedSubject::whereSubjectId($child->subject_id)->whereUserId(Auth::id())->count();

            $child->percent_subject = ($count_course_completed_by_subject / ($count_course_by_subject > 0 ? $count_course_by_subject : 1)) * 100;
            $child->has_course = $this->checkCourseSubject($child->subject_id);
        }

        return view('user::frontend.training_by_title.tree_child', [
            'childs' => $childs,
            'level_subject_arr' => $level_subject_arr
        ]);
    }

    public function violateGetData(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $shedule = DB::query()
            ->select(['a.course_id', 'b.user_id'])
            ->from('el_offline_schedule as a')
            ->leftJoin('el_offline_register as b', 'b.course_id', '=', 'a.course_id')
            ->leftJoin('el_offline_course as c', 'c.id', '=', 'a.course_id')
            ->where('b.user_id', \Auth::id());

        $shedule->whereNotExists(function (Builder $builder) {
            $builder->select(['id'])
                ->from('el_offline_attendance as att')
                ->whereColumn('att.schedule_id', '=', 'a.id')
                ->whereColumn('att.user_id', '=', 'b.user_id')
                ->whereColumn('att.course_id', '=', 'b.course_id');
        });
        $list_shedule = $shedule->get();

        $user_arr = [];
        $course_arr = [];
        foreach ($list_shedule as $item) {
            $user_arr[] = $item->user_id;
            $course_arr[] = $item->course_id;
        }

        $query = OfflineRegisterView::query();
        $query->select([
            'a.id',
            'a.user_id',
            'a.course_id',
            'a.note',
            'course.code as course_code',
            'course.name as course_name',
            'course.course_time',
            'course.start_date',
            'course.end_date',
            'c.name as unit_type_name',
            'd.name as area_name_unit'
        ]);
        $query->from('el_offline_register_view as a');
        $query->leftJoin('el_offline_course as course', 'course.id', '=', 'a.course_id');
        $query->leftjoin('el_unit as b', 'b.id', '=', 'a.unit_id');
        $query->leftjoin('el_unit_type as c', 'c.id', '=', 'b.type');
        $query->leftjoin('el_area as d', 'd.id', '=', 'b.area_id');
        $query->where('a.status', '=', 1);
        $query->where('a.user_id', \Auth::id());
        $query->where(function ($sub) use ($user_arr, $course_arr) {
            $sub->orWhereNotExists(function (Builder $builder) {
                $builder->select(['id'])
                    ->from('el_offline_course_complete as occ')
                    ->whereColumn('occ.user_id', '=', 'a.user_id')
                    ->whereColumn('occ.course_id', '=', 'a.course_id');
            });
            $sub->orWhere(function ($sub2) use ($user_arr, $course_arr) {
                $sub2->whereIn('a.course_id', $course_arr);
                $sub2->whereIn('a.user_id', $user_arr);
            });
        });

        $count = $query->count();
        $query->orderBy('a.' . $sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $profile = ProfileView::whereUserId($row->user_id)->first();
            $course = OfflineCourse::find($row->course_id);

            $row->user_code = $profile->code;
            $row->full_name = $profile->full_name;
            $row->email = $profile->email;
            $row->phone = $profile->phone;
            $row->unit_name_1 = $profile->unit_name;
            $row->unit_name_2 = $profile->parent_unit_name;
            $row->position_name = $profile->position_name;
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            $schedules = OfflineSchedule::query()
                ->select([
                    'a.end_time',
                    'a.lesson_date',
                    'b.absent_id',
                    'b.absent_reason_id',
                    'b.discipline_id',
                ])
                ->from('el_offline_schedule as a')
                ->leftJoin('el_offline_attendance as b', 'b.schedule_id', '=', 'a.id')
                ->where('a.course_id', '=', $row->course_id)
                ->where('b.register_id', '=', $row->id)
                ->get();
            foreach ($schedules as $schedule) {
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00') {
                    $row->time_schedule .= 'Sáng ' . get_date($schedule->lesson_date) . '; ';
                } else {
                    $row->time_schedule .= 'Chiều ' . get_date($schedule->lesson_date) . '; ';
                }

                if ($schedule->absent_id != 0 || $schedule->absent_reason_id != 0 || $schedule->discipline_id != 0) {
                    if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00') {
                        $row->schedule_discipline .= 'Sáng ' . get_date($schedule->lesson_date) . '; ';
                    } else {
                        $row->schedule_discipline .= 'Chiều ' . get_date($schedule->lesson_date) . '; ';
                    }

                    $discipline = Discipline::find($schedule->discipline_id);
                    $absent = Absent::find($schedule->absent_id);
                    $absent_reason = AbsentReason::find($schedule->absent_reason_id);
                    $row->discipline = $discipline ? $discipline->name . '; ' : '';
                    $row->absent = $absent ? $absent->name . '; ' : '';
                    $row->absent_reason = $absent_reason ? $absent_reason->name . '; ' : '';
                }
            }

            $row->attendance = $schedules->count();
            $row->result = 'Không đạt';

            switch ($profile->status_id) {
                case 0:
                    $row->status_user = trans('backend.inactivity');
                    break;
                case 1:
                    $row->status_user = trans('backend.doing');
                    break;
                case 2:
                    $row->status_user = trans('backend.probationary');
                    break;
                case 3:
                    $row->status_user = trans('backend.pause');
                    break;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
