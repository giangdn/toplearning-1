<?php

namespace Modules\Online\Http\Controllers;

use App\Automail;
use App\RattingCourse;
use App\CourseStatistic;
use App\Models\Categories\Area;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\PermissionUser;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Profile;
use App\Scopes\DraftScope;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Modules\Certificate\Entities\Certificate;
use Modules\Online\Entities\MoodleCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseSettingPercent;
use Modules\Online\Entities\OnlineCourseView;
use Modules\Online\Entities\OnlineHistoryEdit;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineCourseDocument;
use Modules\Online\Entities\OnlineCourseUpload;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\PlanApp\Entities\PlanAppTemplate;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Rating\Entities\RatingTemplate;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\TrainingType;
use App\Models\Categories\TrainingObject;
use Modules\ReportNew\Entities\ReportNewExportBC05;
use Modules\ReportNew\Entities\ReportNewExportBC26;
use Modules\TrainingPlan\Entities\TrainingPlan;
use Modules\VirtualClassroom\Entities\VirtualClassroom;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Facades\Module;
use Modules\Offline\Entities\OfflineCourse;
use App\Warehouse;
use Illuminate\Support\Str;
use Modules\Online\Entities\OnlineCourseNote;
use Modules\Online\Entities\OnlineComment;
use Modules\Online\Entities\OnlineRating;
use App\Exports\EvaluateExport;
use Modules\Online\Entities\OnlineCourseAskAnswer;
use Modules\Online\Entities\OnlineCourseLesson;
use Modules\Online\Entities\OnlineCourseActivityFile;
use Modules\Online\Entities\OnlineCourseActivityVideo;
use Modules\Online\Entities\OnlineCourseActivityUrl;
use Modules\Online\Entities\OnlineCourseActivityQuiz;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use App\Models\Categories\TrainingForm;
use Modules\Online\Entities\OnlineRegisterView;

class BackendController extends Controller
{
    public $is_unit = 0;

    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('backend.training.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        $subject_id = $request->input('subject_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_invited = null;
        $check_user_invited = OnlineInviteRegister::query()->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }

        $prefix= \DB::getTablePrefix();
        OnlineCourse::addGlobalScope(new DraftScope(null, null, $user_invited));
        $query = OnlineCourse::query();
        $query->select([
            'el_online_course.id',
            'el_online_course.code',
            'el_online_course.name',
            'el_online_course.isopen',
            'el_online_course.in_plan',
            'el_online_course.start_date',
            'el_online_course.end_date',
            'el_online_course.status',
            'c.name as subject_name',
            'el_online_course.register_deadline',
            'el_online_course.lock_course',
            'el_online_course.created_at',
            'el_online_course.approved_step',
            'el_online_course.created_by',
            'el_online_course.updated_by',
        ]);
//        $query->from('el_online_course');
//        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_online_course.training_program_id');
       $query->leftJoin('el_subject AS c', 'c.id', '=', 'el_online_course.subject_id');
//        $query->leftJoin('el_level_subject as d', 'd.id', '=', 'el_online_course.level_subject_id');
    //    $query->leftJoin('el_profile as e', 'e.user_id', '=', 'el_online_course.created_by');


        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_online_course.name', 'like', '%' . $search . '%');
                $subquery->orWhere('el_online_course.code', 'like', '%' . $search . '%');
            });
        }

        if ($training_program_id) {
            $query->where('el_online_course.training_program_id', '=', $training_program_id);
        }
        if ($level_subject_id){
            $query->where('el_online_course.level_subject_id', '=', $level_subject_id);
        }
        if ($subject_id) {
            $query->where('el_online_course.subject_id', '=', $subject_id);
        }

        if ($start_date) {
            $query->where('el_online_course.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('el_online_course.start_date', '<=', date_convert($end_date, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.online.edit', ['id' => $row->id]);
            $row->register_url = route('module.online.register', ['id' => $row->id]);
            $row->register_secondary_url = route('module.online.register_secondary', ['id' => $row->id]);
            $row->register_deadline = get_date($row->register_deadline);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
//            $row->document = $row->document ? link_download('uploads/'.$row->document) : '';

            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $titles = Titles::where('status', '=', 1)->get();
        $user_invited = false;

        $training_objects = TrainingObject::where('status',1)->get();
        $training_costs = TrainingCost::orderBy('type')->get();
        $course_cost = OnlineCourseCost::where('course_id', '=', $id)->get();
        $total_actual_amount = OnlineCourseCost::getTotalActualAmount($id);
        $total_plan_amount = OnlineCourseCost::getTotalPlanAmount($id);
        $teachers = TrainingTeacher::get();
        $templates = RatingTemplate::get();
        $plan_app_template = PlanAppTemplate::get();
        $training_plan = TrainingPlan::get();
        $certificate = Certificate::all();
        $qrcode_survey_after_course = null;
        $training_forms = TrainingForm::where('training_type_id',1)->get();
        $unit_manager_lv2 = UnitManager::query()
            ->from('el_unit_manager AS a')
            ->join('el_profile AS b', 'b.code', '=', 'a.user_code')
            ->join('el_unit AS c', 'c.code', '=', 'a.unit_code')
            ->where('c.level', '=', 2)
            ->where('b.user_id', '=', Auth::id())->pluck('c.id')->toArray();

        $units = Unit::where('status', '=', 1)->where('level', '=', 2);
        if($unit_manager_lv2){
            $units->whereIn('id', $unit_manager_lv2);
        }
        $units = $units->get();

        $corporations = Unit::where('level', '=', 1)->where('status', '=', 1)->get();
        if ($id) {
            $ratting_course = RattingCourse::where('course_id',$id)->where('type',1)->first();
            $model = OnlineCourse::find($id);
            if (!$model) abort(404);
            $page_title = $model->name;
            $subject = Subject::where('id', $model->subject_id)->first();
            $training_program = TrainingProgram::where('id', $model->training_program_id)->first();
            $level_subject = LevelSubject::find($model->level_subject_id);

            $permission_save = userCan(['online-course-create', 'online-course-edit']);
            $activities = OnlineCourseActivity::getByCourse($id);
            $condition = OnlineCourseCondition::getByCourse($id);

            $unit = explode(',', $model->unit_id);

            $count_activities_quiz = OnlineCourseActivity::where('course_id', '=', $id)
                ->where('activity_id', '=', 2)->count();

            $course_time = preg_replace("/[^0-9]/", '', $model->course_time);
            $course_time_unit = preg_replace("/[^a-z]/", '', $model->course_time);

            /*if (Module::has('Promotion') && array_key_exists('Promotion', Module::allEnabled())) {
                $setting = PromotionCourseSetting::where('course_id', '=', $id)->where('type', '=', 1)->first();
            } else {
                $setting = new Collection();
            }*/
            $qrcode_survey_after_course = json_encode(['course'=>$id,'course_type'=>1,'survey'=>$model->template_id,'type'=>'survey_after_course']);

            !empty($model->title_join_id) ? $get_title_join_model_id = json_decode($model->title_join_id) : $get_title_join_model_id = [];
            !empty($model->title_recommend_id) ? $get_title_recommend_model_id = json_decode($model->title_recommend_id) : $get_title_recommend_model_id = [];
            !empty($model->training_object_id) ? $get_training_object_id = json_decode($model->training_object_id) : $get_training_object_id = [];
            // dd($get_title_recommend_model_id);
            $check_user_invited = OnlineInviteRegister::query()
                ->where('course_id', '=', $id)
                ->where('user_id', '=', Auth::id());
            if ($check_user_invited->exists()){
                $user_invited = true;
            }

            return view('online::backend.online.form', [
                'titles' => $titles,
                'model' => $model,
                'page_title' => $page_title,
                'subject' => $subject,
                'training_program' => $training_program,
                'training_costs' => $training_costs,
                'course_cost' => $course_cost,
                'total_actual_amount' => $total_actual_amount,
                'total_plan_amount' => $total_plan_amount,
                'teachers' => $teachers,
                'templates' => $templates,
                'plan_app_template' => $plan_app_template,
                'training_plan' => $training_plan,
                'is_unit' => $model->unit_id,
                'unit' => $unit,
                'permission_save' => $permission_save,
                'activities' => $activities,
                'condition' => $condition,
                'course_time' => $course_time,
                'course_time_unit' => $course_time_unit,
                'count_activities_quiz' => $count_activities_quiz,
                'certificate' => $certificate,
                'setting' => null,
                'qrcode_survey_after_course' => $qrcode_survey_after_course,
                'units' => $units,
                'unit_manager_lv2' => $unit_manager_lv2,
                'level_subject' => $level_subject,
                'corporations' => $corporations,
                'get_title_join_model_id' => $get_title_join_model_id,
                'get_title_recommend_model_id' => $get_title_recommend_model_id,
                'get_training_object_id' => $get_training_object_id,
                'user_invited' => $user_invited,
                'training_forms' => $training_forms,
                'training_objects' => $training_objects,
                'ratting_course' => $ratting_course,
                'get_menu_child' => $get_menu_child,
                'name_url' => 'training_organizations',
            ]);
        }

        $model = new OnlineCourse();
        $page_title = trans('backend.add_new') ;
        $permission_save = userCan(['online-course-create', 'online-course-edit']);

        return view('online::backend.online.form', [
            'titles' => $titles,
            'model' => $model,
            'page_title' => $page_title,
            'teachers' => $teachers,
            'templates' => $templates,
            'plan_app_template' => $plan_app_template,
            'training_plan' => $training_plan,
            'is_unit' => $this->is_unit,
            'permission_save' => $permission_save,
            'course_time' => null,
            'course_time_unit' => null,
            'certificate' => $certificate,
            'qrcode_survey_after_course' => $qrcode_survey_after_course,
            'units' => $units,
            'unit_manager_lv2' => $unit_manager_lv2,
            'corporations' => $corporations,
            'user_invited' => $user_invited,
            'training_forms' => $training_forms,
            'training_objects' => $training_objects,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'code' => 'required|unique:el_online_course,code,' . $request->id,
            'name' => 'required',
            'in_plan' => 'nullable|exists:el_training_plan,id',
            'course_time' => 'nullable',
            'image' => 'nullable|string',
            'document' => 'nullable|string',
            'num_lesson' => 'nullable',
            // 'action_plan' => 'required|in:0,1',
            'start_date' => 'required|date_format:d/m/Y',
            // 'plan_app_template' => 'required_if:action_plan,1|nullable|integer',
            // 'plan_app_day' => 'required_if:action_plan,1|nullable|integer|max:1000',
        ], $request, OnlineCourse::getAttributeName());
        $course_time_unit = $request->post('course_time_unit');
        $unit_id = $request->post('unit_id');

        $subject = Subject::find($request->subject_id);

        $model = OnlineCourse::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        $model->has_cert = $request->post('has_cert', 0);
        $model->start_date = date_convert($request->input('start_date'));
        $model->end_date = $request->input('end_date') ? date_convert($request->input('end_date'), '23:59:59') : null;
        $model->register_deadline = $request->input('register_deadline') ? date_convert($request->input('register_deadline'), '23:59:59') : null;
        if($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image);
        }
        $model->document = path_upload($model->document);
        $model->unit_id = is_array($unit_id) ? implode(',', $unit_id) : null;
        $model->course_time = $request->input('course_time');
        $model->course_time_unit = $course_time_unit;
        $model->level_subject_id = @$subject->level_subject_id;
        // $model->rating_end_date = $request->input('rating_end_date') ? date_convert($request->input('rating_end_date'), '23:59:59') : null;

        $model->title_join_id = is_array($request->title_join_id) ? json_encode($request->title_join_id) : '';
        $model->title_recommend_id = is_array($request->title_recommend_id) ? json_encode($request->title_recommend_id) : '';
        $model->training_object_id = is_array($request->training_object_id) ? json_encode($request->training_object_id) : '';

        if ($model->end_date) {
            if ($model->start_date > $model->end_date) {
                json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
            }

            if ($model->register_deadline) {
                if ($model->register_deadline >= $model->end_date) {
                    json_message('Hạn đăng ký phải trước Ngày kết thúc', 'error');
                }
            }
        }

        if (empty($request->id)) {
            if ($model->start_date < date('Y-m-d')) {
                json_message('Ngày bắt đầu tính từ hiện tại', 'error');
            }
        }

        $date_original = null;
        if (empty($model->id)) {
            $model->created_by = Auth::id();
            $model->status = 2;
        }else{
            $date_original = OnlineCourse::where(['id' => $request->post('id')])->value('start_date');
        }

        $model->updated_by = Auth::id();

        $save = $model->save();
        if ($save) {
            /********update thống kê khóa học **********/
            if (empty($request->id))
                CourseStatistic::update_course_insert_statistic($model->id,1);
            else
                CourseStatistic::update_course_update_statistic($model->id,1,$date_original);
            /*********************end***********************/

            /*************Update BC 26*******************/
            if ($request->in_plan){
                ReportNewExportBC26::query()
                ->updateOrCreate([
                    'training_plan_id' => $request->in_plan,
                    'subject_id' => $request->subject_id,
                    'year' => date('Y')
                ],[
                    'course_action_'.$request->course_action => ($request->course_action ? 1 : 0)
                ]);
            }
            /*******************************************/
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.online.edit', ['id' => $model->id])
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function saveTutorial(Request $request) {
        $this->validateRequest([
            'type_tutorial' => 'required',
            'content_tutorial' => "required_if:type_tutorial,1",
            'files_tutorial' => "required_if:type_tutorial,2|array|min:1",
            'files_tutorial.*' => 'required_if:type_tutorial,2|mimes:docx,xlsx,pdf|max:4096',
        ], $request, OnlineCourse::getAttributeName());
        $type_tutorial = $request->type_tutorial;
        $flag = $request->flag;
        if ($type_tutorial == 2 && $flag == 0) {
            if ($request->hasfile('files_tutorial')) {
                foreach ($request->file('files_tutorial') as $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) . '-' . time() . '-' . Str::random(10) . '.' . $extension;
                    $storage = \Storage::disk('upload');
                    $new_paths[] = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);
                }
                $content = json_encode($new_paths);
            } else {
                return back()->with('false', 'Chưa chọn file');
            }
        } else if ($type_tutorial == 1) {
            $content = $request->content_tutorial;
        } else {
            $content = $request->content_of_id;
        }

        $model = OnlineCourse::find($request->id);
        $model->type_tutorial = $request->type_tutorial;
        $model->tutorial = $content;
        if ($model->save()) {
            OnlineCourseView::where('id',$request->id)->update(['type_tutorial'=>$request->type_tutorial,'tutorial'=>$content]);
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.online.edit', ['id' => $model->id])
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $course = OnlineCourse::find($id);
            $result = OnlineResult::where('course_id', '=', $id);
            if ($result->exists() || $course->status == 1){
                continue;
            }

            ReportNewExportBC05::query()
                ->where('course_id', '=', $id)
                ->where('course_type', '=', 1)
                ->delete();

            Quiz::where('course_id', '=', $id)
                ->where('course_type', '=', 1)
                ->update(['course_id' => 0, 'course_type' => 0]);

            VirtualClassroom::where('course_id', '=', $id)
                ->update(['course_id' => 0]);

            CourseStatistic::update_course_delete_statistic(1,$course->start_date);

            if ($course->delete()){
                $onlineCourse = OnlineCourse::find($id);
                $data = OnlineRegister::select('id','user_id','course_id')->with('user:user_id,code,firstname,lastname,gender,email')->where(['course_id'=>$id,'status'=>1])->get();
                foreach ($data as $item) {
                    $signature = getMailSignature($item->user_id);
                    $params = [
                        'signature' => $signature,
                        'gender' => $item->user->gender=='1'?'Anh':'Chị',
                        'full_name' => $item->user->full_name,
                        'course_code' => $onlineCourse->code,
                        'course_name' => $onlineCourse->name
                    ];
                    $user_id = [$item->user_id];
                    $automail = new Automail();
                    $automail->template_code = 'delete_course';
                    $automail->params = $params;
                    $automail->users = $user_id;
                    $automail->check_exists = true;
                    $automail->check_exists_status = 0;
                    $automail->object_id = $item->id;
                    $automail->object_type = 'delete_course_online';
                    $automail->addToAutomail();
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxGetSubject(Request $request)
    {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
        ], $request, [
            'training_program_id' => 'Chương trình đào tạo',
        ]);

        $training_program_id = $request->input('training_program_id');
        $subjects = Subject::where('training_program_id', '=', $training_program_id)->get();
        json_result($subjects);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);
        $user_invited = null;
        $check_user_invited = OnlineInviteRegister::query()->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                if ($user_invited && in_array($id, $user_invited)){
                    continue;
                }
                $model = OnlineCourse::findOrFail($id);
                $model->isopen = $status;
                $model->save();
            }
        } else {
            $model = OnlineCourse::findOrFail($ids);
            $model->isopen = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function saveObject($course_id, Request $request)
    {
        $this->validateRequest([
            'type' => 'required|in:1,2',
        ], $request, [
            'title' => 'Chức danh',
            'unit' => 'Đơn vị',
            'type' => 'Loại đối tượng',
        ]);

        $object_type = $request->post('object_type');
        $titles = $request->post('title');
        $units = $request->post('unit');
        $areas = $request->post('area4');
        $type = $request->post('type');

        if (!$units && $titles) {
            json_message('Chưa chọn đơn vị', 'error');
        }

        if ($units && $titles){
            if (count($units) > 1){
                json_message('Khi chọn chức danh. Chỉ được chọn 1 đơn vị', 'error');
            }else{
                $unit = Unit::find($units[0]);
                foreach ($titles as $item) {
                    if (!Titles::where('id', '=', $item)->exists()) {
                        continue;
                    }

                    if (!Unit::where('id', '=', $unit->id)->exists()) {
                        continue;
                    }

                    if (OnlineObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->whereNull('title_id')->exists()) {
                        continue;
                    }

                    if (OnlineObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->where('title_id', '=', $item)->exists()) {
                        continue;
                    }

                    if (OnlineObject::where('course_id', '=', $course_id)->whereNull('unit_id')->where('title_id', '=', $item)->exists()) {
                        OnlineObject::where('course_id', '=', $course_id)
                            ->whereNull('unit_id')
                            ->where('title_id', '=', $item)
                            ->update([
                                'unit_id' => $unit->id,
                                'unit_level' => $unit->level,
                            ]);
                    }else{
                        $model = new OnlineObject();
                        $model->title_id = $item;
                        $model->unit_id = $unit->id;
                        $model->unit_level = $unit->level;
                        $model->type = $type;
                        $model->course_id = $course_id;
                        $model->created_by = Auth::id();
                        $model->updated_by = Auth::id();
                        $model->save();
                    }
                }
            }
        }

        if ($units && !$titles) {
            foreach ($units as $item) {

                if (OnlineObject::where('course_id', '=', $course_id)
                    ->where('unit_id', '=', $item)
                    ->exists()) {
                    continue;
                }

                if (!Unit::where('id', '=', $item)->exists()) {
                    continue;
                }

                $unit = Unit::find($item);

                $model = new OnlineObject();
                $model->unit_id = $item;
                $model->unit_level = $unit->level;
                $model->type = $type;
                $model->course_id = $course_id;
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
                $model->save();
            }
        }

       /* if ($object_type == 3) {
            if ($areas) {
                foreach ($areas as $area) {
                    $model = new OnlineObject();
                    $model->area1 = $request->post('area1');
                    $model->area2 = $request->post('area2');
                    $model->area3 = $request->post('area3');
                    $model->area4 = $area;
                    $model->type = $type;
                    $model->course_id = $course_id;
                    $model->created_by = Auth::id();
                    $model->updated_by = Auth::id();
                    $model->save();
                }
            }
            else {
                $model = new OnlineObject();
                $model->area1 = $request->post('area1');
                $model->area2 = $request->post('area2');
                $model->area3 = $request->post('area3');
                $model->area4 = null;
                $model->type = $type;
                $model->course_id = $course_id;
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
                $model->save();
            }
        }*/

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = Auth::id();
        $history_edit->tab_edit = 'Thêm đối tượng khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 1;
        $history_edit->save();

        return \response()->json([
            'status' => 'success',
            'message' => 'Thêm đối tượng thành công',
        ]);
    }

    public function getObject($course_id, Request $request)
    {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = OnlineObject::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
            'c.name AS unit_name'
        ]);

        $query->from('el_online_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->where('a.course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->area4) {
                $row->area_name = @Area::find($row->area4)->name;
            }
            elseif ($row->area3) {
                $row->area_name = @Area::find($row->area3)->name;
            }
            elseif ($row->area2) {
                $row->area_name = @Area::find($row->area2)->name;
            }
            else {
                $row->area_name = @Area::find($row->area1)->name;
            }
        }

        json_result([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function removeObject($course_id, Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Đối tượng',
        ]);

        $item = $request->input('ids');
        OnlineObject::destroy($item);

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = Auth::id();
        $history_edit->tab_edit = 'Xoá đối tượng khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 1;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveCost($course_id, Request $request)
    {
        $find = [',', ';', '.'];
        $cost_ids = $request->post('id');
        $plan_amounts = str_replace($find, '', $request->post('plan_amount'));
        $actual_amounts = str_replace($find, '', $request->post('actual_amount'));
        $notes = $request->post('note');
        // dd($cost_ids, $plan_amounts, $actual_amounts);

        foreach ($cost_ids as $key => $cost_id) {

            if (OnlineCourseCost::checkCostExists($course_id, $cost_id)) {
                OnlineCourseCost::updateOrCreate(['course_id'=>$course_id,'cost_id'=>$cost_id],
                        [
                        'plan_amount' => (float)$plan_amounts[$key] ? $plan_amounts[$key] : 0,
                        'actual_amount' => (float)$actual_amounts[$key] ? $actual_amounts[$key] : 0,
                        'notes' => $notes[$key] ? $notes[$key] : '',
                    ]);
                continue;
            }
            if($plan_amounts[$key] > 0 || $actual_amounts[$key] > 0) {
                $model = new OnlineCourseCost();
                $model->cost_id = $cost_id;
                $model->plan_amount = $plan_amounts[$key] ? $plan_amounts[$key] : 0;
                $model->actual_amount = $actual_amounts[$key] ? $actual_amounts[$key] : 0;
                $model->notes = $notes[$key] ? $notes[$key] : '';
                $model->course_id = $course_id;
                $model->save();
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu chi phí đào tạo thành công',
        ]);
    }

    public function ajaxGetCourseCode(Request $request)
    {
        $this->validateRequest([
            'subject_id' => 'required',
        ], $request, [
            'subject_id' => 'Mã học phần',
        ]);

        $user_role = UserRole::query()
            ->from('el_user_role as a')
            ->leftJoin('el_roles as b', 'b.id', '=', 'a.role_id')
            ->where('a.user_id', '=', Auth::id())
            ->first('b.code');

        $subject_id = $request->input('subject_id');
        $id = $request->id;
        $subject = Subject::find($subject_id);
        $courses = OnlineCourse::where('subject_id', '=', $subject->id)->get();
        $level_subject = LevelSubject::find($subject->level_subject_id);

        $count_course = count($courses);

        $check_count_course = '';
        for ($i = 1; $i <= $count_course; $i++) {
            $count = '00'.$i;
            $get_course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
            $check_subject_course_code = OnlineCourse::where('code',$get_course_code)->first();
            if(empty($check_subject_course_code)) {
                $check_count_course = $count;
                break;
            }
        }

        if( !empty($check_count_course) ) {
            $course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$check_count_course;

        } else {
            $count_course = count($courses) + 1;
            $count = '00'.$count_course;
            $course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
        }
        if($id) {
            $get_course_code_subject = OnlineCourse::find($id);
            if($get_course_code_subject->subject_id == $subject_id ) {
                $course_code = $get_course_code_subject->code;
            }
        }

        return response()->json([
            'id' => count($courses),
            'course_code' => $course_code,
            'description' => $subject->description,
            'content' => $subject->content,
            'level_subject_name' => @$level_subject->name,
        ]);
    }

//    public function approve(Request $request)
//    {
//        $user_invited = null;
//        $check_user_invited = OnlineInviteRegister::query()->where('user_id', '=', Auth::id());
//        if ($check_user_invited->exists()){
//            $user_invited = $check_user_invited->pluck('course_id')->toArray();
//        }
//
//        $ids = $request->input('ids', null);
//        $status = $request->input('status', null);
//        $note = $request->input('note', null);
//        foreach ($ids as $id) {
//            if ($user_invited && in_array($id, $user_invited)){
//                continue;
//            }
//            (new ApprovedModelTracking())->updateApprovedTracking(OnlineCourse::getModel(),$id,$status,$note);
////            $query = OnlineCourse::findOrFail($id);
////            $query->status=$status;
////            $query->lock_course=1;
////            $query->save();
//            $this->updateEmailCourseObject($id);
//        }
//
//        json_message('Duyệt thành công','success');
//    }

    public function lockCourse(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);
        $user_invited = null;
        $check_user_invited = OnlineInviteRegister::query()->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }
        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                if ($user_invited && in_array($id, $user_invited)){
                    continue;
                }
                $model = OnlineCourse::findOrFail($id);
                $model->lock_course = $status;
                $model->save();
            }
        } else {
            $model = OnlineCourse::findOrFail($ids);
            $model->lock_course = $status;
            $model->save();
        }
 
        json_result([
            'status' => 'success',
            'message' =>trans('lageneral.successful_save'),
        ]);
    }

    public function gotoMoodleCourse($course_id, Request $request) {
        $course = OnlineCourse::findOrFail($course_id);
        $link = MoodleCourse::getLinkToMoodle($request->url, 1, $course->moodlecourseid);
        return redirect($link);
    }

    public function saveCondition($course_id, Request $request)
    {
        $activity = $request->post('activity', []);
        $condition = OnlineCourseCondition::firstOrNew(['course_id' => $course_id]);
        $condition->course_id = $course_id;
        $condition->rating = $request->post('complaterating', 0);
        $condition->orderby = $request->post('orderby', 0);
        $condition->activity = implode(',', $activity);
        $condition->grade_methor = $request->post('grade_methor', null);

        if ($condition->save()) {
            OnlineCourseActivity::where('course_id', '=', $course_id)
                ->whereIn('id', $activity)
                ->where('status', '=', 0)
                ->update([
                    'status' => 1,
                ]);

            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = Auth::id();
            $history_edit->tab_edit = 'Điều kiện hoàn thành';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 1;
            $history_edit->save();

            json_message(trans('lageneral.successful_save'));
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function sendMailApprove(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Khóa học'
        ]);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $course = OnlineCourse::find($id);
            $users = [];
            if ($course->status != 1) {
                $automail = new Automail();
                $automail->template_code = 'approve_course';
                $automail->params = [
                    'code' => $course->code,
                    'name' => $course->name,
                    'start_date' => get_date($course->start_date),
                    'end_date' => get_date($course->end_date),
                    'url' => route('module.online.management')
                ];
                $automail->users = $users;
                $automail->check_exists = true;
                $automail->check_exists_status = 0;
                $automail->object_id = $course->id;
                $automail->object_type = 'approve_online';
                $automail->addToAutomail();
            }
        }

        json_message('Gửi mail thành công');
    }

    public function sendMailChange(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Khóa học'
        ]);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $course = OnlineCourse::find($id);
            $users = OnlineRegister::where('course_id', '=', $id)
                ->where('status', '=', 1)
                ->pluck('user_id')
                ->toArray();

            $automail = new Automail();
            $automail->template_code = 'course_change';
            $automail->params = [
                'code' => $course->code,
                'name' => $course->name,
                'start_date' => get_date($course->start_date),
                'end_date' => get_date($course->end_date),
                'url' => route('module.online.detail', ['id' => $id])
            ];
            $automail->users = $users;
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course->id;
            $automail->object_type = 'course_online_change';
            $automail->addToAutomail();
        }

        json_message('Gửi mail thành công');
    }

    public function getDataHistory($course_id, Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineHistoryEdit::query();
        $query->select([
            'a.*',
            'b.code',
            'b.firstname',
            'b.lastname',
        ]);
        $query->from('el_online_history_edit AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('type', '=', 1);

        $count = $query->count();
        $query->orderBy('a.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->fullname = $row->lastname . ' ' . $row->firstname . ' (' . $row->code . ')';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    //Quản lý upload file
    public function uploadfile(Request $request) {
        $this->validate($request, [
            'filenames' => "required|string",
        ]);

        $course_id = $request->course_id;
        if ($request->filenames) {
            $model = new OnlineCourseUpload();
            $model->upload = path_upload($request->filenames);
            $model->course_id = $course_id;
            $model->save();
        }else{
            return back()->with('false', 'Chưa chọn file');
        }

        return back()->with('success', 'Đã tải lên thư vện file');
    }
    //end quản lý file

    //Thư viên file
    public function getDataLibraryFile($course_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourseUpload::query();
        $query->select('*');
        $query->from('el_online_course_upload as a');
        $query->where('course_id',$course_id);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('upload', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach($rows as $row){
            $row->uploadName = basename($row->upload);
            $row->uploadFile = link_download('uploads/'.$row->upload);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    //end thư viện file

    public function removeLibraryFile(Request $request) {
        $ids = $request->input('ids', null);
        OnlineCourseUpload::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function getChild($course_id, Request $request){
        $unit_id = $request->id;
        $unit = Unit::find($unit_id);

        $childs = Unit::where('parent_code', '=', $unit->code)->get(['id', 'name', 'code']);

        $count_child = [];
        $page_child = [];
        foreach ($childs as $item){
            $count_child[$item->id] = Unit::countChild($item->code);
            $page_child[$item->id] = route('module.online.get_tree_child', ['id' => $course_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }

    public function getTreeChild($course_id, Request $request) {
        $parent_code = $request->parent_code;
        return view('online::backend.online.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    // HỌC VIÊN GHI CHÉP
    public function getUserNoteEvaluate($course_id,Request $request) {
        $search = $request->input('search_note');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineRegisterView::query();
        $query->select([
            'a.user_id',
            'a.user_type',
            'a.course_id',
            'a.status',
            'a.full_name',
            'a.unit_name',
            'a.title_name',
        ]);
        $query->from('el_online_register_view as a');
        $query->whereExists(function ($queryExist) {
            $queryExist->select('user_id')->from('el_online_course_note as b')->whereColumn('a.user_id','=','b.user_id');
        });
        $query->orWhereExists(function ($queryExist2) {
            $queryExist2->select('user_id')->from('el_online_rating as c')->whereColumn('a.user_id','=','c.user_id');
        });
        $query->leftJoin('el_quiz_user_secondary as e',function ($sub){
            $sub->on('e.id','=','a.user_id')->where('a.user_type', '=', 2);
        });
        $query->where('a.course_id',$course_id);
        $query->where('a.status',1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('e.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.full_name', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach($rows as $row){
            $row->fullname = $row->user_type == 1 ? $row->full_name : $row->second_name;
            $check_ratting = OnlineRating::where('user_id',$row->user_id)->exists();
            if($check_ratting) {
                $row->view_evaluate = route('module.online.view_user_note_evaluate', ['id' => $row->user_id,'course_id'
                => $course_id,'type' => 2, 'user_type' => $row->user_type]);
            } else {
                $row->view_evaluate = '';
            }
            $check_note = OnlineCourseNote::where('user_id',$row->user_id)->exists();
            if($check_note) {
                $row->view_note = route('module.online.view_user_note_evaluate', ['id' => $row->user_id,'course_id' =>
                $course_id,'type' => 1, 'user_type' => $row->user_type]);
            } else {
                $row->view_note = '';
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function viewUserNoteEvaluate($id,$course_id,$type, Request $request) {
        $user_type = $request->user_type;

        $get_rating = null;
        if ($type == 1) {
            $get_user_notes_evaluates = OnlineCourseNote::select('note')->where('user_id',$id)->where('user_type', '=', $user_type)->get();
        }
        if ($type == 2) {
            $get_user_notes_evaluates = OnlineComment::select('content')->where('user_id',$id)->where('user_type', '=', $user_type)->get();
            $get_rating = OnlineRating::where('user_id',$id)->first();
        }
        if ($user_type == 1){
            $profile = Profile::where('user_id',$id)->first();
            $fullname = $profile->lastname .' '. $profile->firstname;
        }else{
            $profile = QuizUserSecondary::find($id);
            $fullname = $profile->name;
        }

        $course = OnlineCourse::find($course_id);
        $page_title = $course->name;
        return view('online::backend.online.form.view_note_evaluate',[
            'id' => $id,
            'type' => $type,
            'fullname' => $fullname,
            'get_user_notes_evaluates'=>$get_user_notes_evaluates,
            'get_rating' => $get_rating,
            'course_id' => $course_id,
            'page_title' => $page_title,
            'course' => $course,
            'user_type' => $user_type
        ]);
    }

    public function getContentEvaluate($id,$course_id,Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_type = $request->user_type;

        $query = OnlineComment::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname'
        ]);
        $query->from('el_online_comment as a');
        $query->leftJoin('el_profile as b','b.user_id','=','a.user_id');
        $query->where('a.course_id',$course_id);
        $query->where('a.user_id',$id);
        $query->where('a.user_type',$user_type);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach($rows as $row){
            $row->fullname = $row->lastname . ' ' . $row->firstname;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeContentEvaluate(Request $request) {
        $ids = $request->input('ids', null);
        OnlineComment::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function export($id,$course_id, $user_type)
    {
        return (new EvaluateExport($id,$course_id, $user_type))->download('danh_sach_binh_luan_'. date('d_m_Y') .'.xlsx');
    }

    // HỌC VIÊN HỎI ĐÁP
    public function getUserAskAnswer($course_id,Request $request) {
        $search = $request->input('search_note');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourseAskAnswer::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'c.name as secon_name',
        ]);
        $query->from('el_online_course_ask_answer as a');
        $query->leftJoin('el_profile as b',function ($sub){
            $sub->on('a.user_id_ask','=','b.user_id')
                ->where('a.user_type_ask', '=', 1);
        });
        $query->leftJoin('el_quiz_user_secondary as c',function ($sub){
            $sub->on('a.user_id_ask','=','c.id')
                ->where('a.user_type_ask', '=', 2);
        });
        $query->where('a.course_id',$course_id);
        // $query->where('b.status',1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach($rows as $row){
            $row->fullname = $row->user_type_ask == 1 ? $row->lastname . ' ' . $row->firstname : $row->secon_name;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveAnswer(Request $request) {
        $this->validateRequest([
            'answer' => 'nullable',
            'regid' => 'required',
        ], $request);

        $answer = $request->input('answer');
        $register_id = $request->input('regid');

            if(OnlineCourseAskAnswer::find($register_id)){
                $model = OnlineCourseAskAnswer::find($register_id);
                $model->answer = $answer;
                $model->user_id_answer = \Auth::id();
                $model->save();
                json_message('ok');
            }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function ajaxIsopenStatus(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Offline',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status');
        foreach ($ids as $id) {
            $model = OnlineCourseAskAnswer::findOrFail($id);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    // BÀI HỌC
    public function getLesson($course_id,Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourseLesson::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_online_course_lesson as a');
        $query->where('a.course_id',$course_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveLesson($course_id, Request $request) {
        $this->validateRequest([
            'lesson_name' => 'required',
        ], $request, OnlineCourseLesson::getAttributeName());

        $lesson_name = $request->input('lesson_name');
        $model = new OnlineCourseLesson();
        $model->lesson_name = $lesson_name;
        $model->course_id = $course_id;
        $model->save();
        json_message(trans('lageneral.successful_save'));

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function removeLesson($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Bài học',
        ]);

        $ids = $request->input('ids');
        foreach($ids as $id) {
            $checkActivity = OnlineCourseActivity::where('lesson_id',$id)->get();
            if(!$checkActivity->isEmpty()){
                json_result([
                    'status' => 'error',
                    'message' => 'Xóa thất bại vì bài học có chứa học phần',
                ]);
            } else {
                OnlineCourseLesson::destroy($id);
                json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
            }
        }
    }

    public function getSettingPercent($course_id, Request $request){
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $condition = OnlineCourseCondition::where('course_id', '=', $course_id)->first();
        $activity_condition = $condition ? explode(',', $condition->activity) : [];

        $query = OnlineCourseActivity::query();
        $query->whereIn('id', $activity_condition);
        $query->where('course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $setting_percent = OnlineCourseSettingPercent::query()
                ->where('course_id', '=', $course_id)
                ->where('course_activity_id', '=', $row->id)
                ->first();

            $row->score = $setting_percent && !is_null($setting_percent->score) ? $setting_percent->score : '';
            $row->percent = $setting_percent && !is_null($setting_percent->percent) ? $setting_percent->percent : '';

            $row->disabled = '';
            if ($row->activity_id == 1 || $row->activity_id == 2){
                $row->disabled = 'readonly';
            }
        }

        json_result([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function saveSettingScorePercent($course_id, Request $request){
        $condition = OnlineCourseCondition::where('course_id', '=', $course_id)->first();
        $activity_condition = explode(',', $condition->activity);

        $query = OnlineCourseActivity::query();
        $query->whereIn('id', $activity_condition);
        $query->where('course_id', '=', $course_id);
        $rows = $query->get();

        $percent = $request->percent;
        $score = $request->score;

        if (array_sum($percent) != 100){
            json_message('Tổng % là 100', 'error');
        }

        foreach ($rows as $key => $row){
            OnlineCourseSettingPercent::updateOrCreate([
                'course_id' => $course_id,
                'course_activity_id' => $row->id,
            ], [
                'percent' => isset($percent[$row->id]) ? $percent[$row->id] : null,
                'score' => isset($score[$row->id]) ? $score[$row->id] : null,
            ]);
        }

        json_message(trans('lageneral.successful_save'));
    }

    // SAO CHÉP KHÓA HỌC
    public function copy(Request $request) {

        $ids = $request->input('ids', null);
        $user_role = UserRole::query()
            ->from('el_user_role as a')
            ->leftJoin('el_roles as b', 'b.id', '=', 'a.role_id')
            ->where('a.user_id', '=', Auth::id())
            ->first('b.code');

        foreach ($ids as $id) {
            $getCourse = OnlineCourse::findOrFail($id)->toArray();
            $getLessonCourses = OnlineCourseLesson::where('course_id',$id)->get();

            $getActivityFiles = OnlineCourseActivityFile::where('course_id',$id)->get()->toArray();
            $getActivityVideos = OnlineCourseActivityVideo::where('course_id',$id)->get()->toArray();
            $getActivityUrls = OnlineCourseActivityUrl::where('course_id',$id)->get()->toArray();
            $getActivityQuizzes = OnlineCourseActivityQuiz::where('course_id',$id)->get()->toArray();
            $getActivityScorms = OnlineCourseActivityScorm::where('course_id',$id)->get()->toArray();
            $getActivityVirtualClassrooms = VirtualClassroom::where('course_id',$id)->get()->toArray();

            $getCourseCondition = OnlineCourseCondition::where('course_id',$id)->first();

            $saveCourse = new OnlineCourse();
            $saveCourse->fill($getCourse);

            $courses = OnlineCourse::where('subject_id', '=', $getCourse['subject_id'])->get();
            $subject = Subject::find($getCourse['subject_id']);
            $count_course = count($courses);

            $check_count_course = '';
            for ($i = 1; $i <= $count_course; $i++) {
                $count = '00'.$i;
                $course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
                $check_subject_course_code = OnlineCourse::where('code',$course_code)->first();
                if(empty($check_subject_course_code)) {
                    $check_count_course = $count;
                    break;
                }
            }

            if( !empty($check_count_course) ) {
                $saveCourse->code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$check_count_course;
            } else {
                $count_course = count($courses) + 1;
                $count = '00'.$count_course;
                $saveCourse->code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
            }
            $saveCourse->save();
            CourseStatistic::update_course_insert_statistic($saveCourse->id,1);

            foreach($getLessonCourses as $getLessonCourse) {
                $getActivityCourses = OnlineCourseActivity::where('course_id',$id)->where('lesson_id',$getLessonCourse->id)->get()->toArray();
                $saveLessonCourse = new OnlineCourseLesson();
                $saveLessonCourse->course_id = $saveCourse->id;
                $saveLessonCourse->lesson_name = $getLessonCourse->lesson_name;
                $saveLessonCourse->save();

                foreach($getActivityCourses as $getActivityCourse) {
                    if($getActivityFiles) {
                        foreach($getActivityFiles as $getActivityFile) {
                            if($getActivityFile['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 3) {
                                $saveActivityFile = new OnlineCourseActivityFile();
                                $saveActivityFile->fill($getActivityFile);
                                $saveActivityFile->course_id = $saveCourse->id;
                                $saveActivityFile->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityFile->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityVideos) {
                        foreach($getActivityVideos as $getActivityVideo) {
                            if($getActivityVideo['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 5) {
                                $saveActivityVideo = new OnlineCourseActivityVideo();
                                $saveActivityVideo->fill($getActivityVideo);
                                $saveActivityVideo->course_id = $saveCourse->id;
                                $saveActivityVideo->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityVideo->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityUrls) {
                        foreach($getActivityUrls as $getActivityUrl) {
                            if($getActivityUrl['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 4) {
                                $saveActivityUrl = new OnlineCourseActivityUrl();
                                $saveActivityUrl->fill($getActivityUrl);
                                $saveActivityUrl->course_id = $saveCourse->id;
                                $saveActivityUrl->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityUrl->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityQuizzes) {
                        foreach($getActivityQuizzes as $getActivityQuiz) {
                            if($getActivityQuiz['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 2) {
                                $saveActivityQuiz = new OnlineCourseActivityQuiz();
                                $saveActivityQuiz->fill($getActivityQuiz);
                                $saveActivityQuiz->course_id = $saveCourse->id;
                                $saveActivityQuiz->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityQuiz->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityScorms) {
                        foreach($getActivityScorms as $getActivityScorm) {
                            if($getActivityScorm['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 1) {
                                $saveActivityScorm = new OnlineCourseActivityScorm();
                                $saveActivityScorm->fill($getActivityScorm);
                                $saveActivityScorm->course_id = $saveCourse->id;
                                $saveActivityScorm->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityScorm->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityVirtualClassrooms) {
                        foreach($getActivityVirtualClassrooms as $getActivityVirtualClassroom) {
                            if($getActivityVirtualClassroom['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 6) {
                                $saveActivityVirtualClassroom = new VirtualClassroom();
                                $saveActivityVirtualClassroom->fill($getActivityVirtualClassroom);
                                $saveActivityVirtualClassroom->course_id = $saveCourse->id;
                                $saveActivityVirtualClassroom->code = $getActivityVirtualClassroom['code'] . rand(2,10);
                                $saveActivityVirtualClassroom->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityVirtualClassroom->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                }
            }

            if(!empty($getCourseCondition)) {
                $saveCourseCondition = new OnlineCourseCondition();
                $saveCourseCondition->course_id = $saveCourse->id;
                $saveCourseCondition->rating = $getCourseCondition->rating;
                $saveCourseCondition->orderby = $getCourseCondition->orderby;
                $saveCourseCondition->grade_methor = $getCourseCondition->grade_methor;

                if(!empty($getCourseCondition->activity)) {
                    $activityCourseConditions = explode(',', $getCourseCondition->activity);
                    foreach($activityCourseConditions as $activityCourseCondition) {
                        $getActivityOfCondition = OnlineCourseActivity::find($activityCourseCondition)->toArray();
                        $getIdActivityNewCourse = OnlineCourseActivity::where('activity_id',$getActivityOfCondition['activity_id'])->where('num_order',$getActivityOfCondition['num_order'])->where('course_id', $saveCourse->id)->first();
                        $getIdActivityNewCourses[] = $getIdActivityNewCourse->id;
                    }
                    $saveCourseCondition->activity = implode(",", $getIdActivityNewCourses);
                }

                $saveCourseCondition->save();
            }
        }

        json_message('Sao chép thành công');
    }

    public function imageActivitySave($id, Request $request) {
        $this->validateRequest([
            'image_activity' => 'required',
        ], $request, OnlineCourse::getAttributeName());

        $model = OnlineCourse::find($id);
        if($request->image_activity) {
            $sizes = config('image.sizes.medium');
            $model->image_activity = upload_image($sizes, $request->image_activity);
        }
        $model->save();

        return response()->json([
            'message' => trans('backend.save_success'),
            'status' => 'success'
        ]);
    }

    // ĐÁNH GIÁ KHÓA HỌC
    public function saveRattingCourse($id, Request $request) {
        $model = RattingCourse::firstOrNew(['course_id' => $id, 'type' => 1]);
        $model->course_id = $id;
        $model->teacher = $request->teacher;
        $model->program_content = $request->program_content;
        $model->organization = $request->organization;
        $model->quality_course = $request->quality_course;
        $model->type = 1;
        $model->save();

        return response()->json([
            'message' => trans('backend.save_success'),
            'status' => 'success'
        ]);
    }
}
