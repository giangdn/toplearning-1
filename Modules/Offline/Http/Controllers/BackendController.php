<?php

namespace Modules\Offline\Http\Controllers;

use App\Automail;
use App\CourseStatistic;
use App\CourseView;
use App\Http\Controllers\Backend\CommitMonthController;
use App\Models\Categories\Area;
use App\Models\Categories\CommitMentTitle;
use App\Models\Categories\CommitMonth;
use App\Models\Categories\District;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Position;
use App\Models\Categories\TitleRank;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\PermissionUser;
use App\Models\Categories\Province;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Unit;
use App\Profile;
use App\Role;
use App\Scopes\DraftScope;
use App\UserRole;
use Carbon\Carbon;
use core\plugininfo\mod;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\RequiredIf;
use Modules\Certificate\Entities\Certificate;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseUpload;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineCourseView;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineScheduleParent;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Offline\Entities\OfflineCondition;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Online\Entities\OnlineHistoryEdit;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\PlanApp\Entities\PlanAppTemplate;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Quiz\Entities\Quiz;
use Modules\Rating\Entities\RatingTemplate;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TeacherType;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\StudentCost;
use App\Models\Categories\Course;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingType;
use App\Models\Categories\TrainingObject;
use Modules\ReportNew\Entities\ReportNewExportBC05;
use Modules\ReportNew\Entities\ReportNewExportBC08;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use Modules\ReportNew\Entities\ReportNewExportBC26;
use Modules\TrainingPlan\Entities\TrainingPlan;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Facades\Module;
use User\Acl\Rule;
use App\Warehouse;
use Illuminate\Support\Str;
use App\TypeCost;
use App\RattingCourse;

class BackendController extends Controller
{
    public $is_unit = 0;

    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        \Session::forget('errors');
        $training_form = TrainingForm::where('training_type_id',2)->get();

        // return view('offline::backend.offline.index', [
        //     'training_forms' => $training_form
        // ]);
        return view('backend.training.index',[
            'training_forms' => $training_form,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        $subject_id = $request->input('subject_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $training_form_id = $request->input('training_form_id');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_invited = null;
        $check_user_invited = OfflineInviteRegister::query()->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }

        $prefix= \DB::getTablePrefix();
        OfflineCourse::addGlobalScope(new DraftScope(null, null, $user_invited));
        $query = OfflineCourse::query();
        $query->select([
            'el_offline_course.id',
            'el_offline_course.name',
            'el_offline_course.code',
            'el_offline_course.in_plan',
            'el_offline_course.isopen',
            'el_offline_course.register_deadline',
            'el_offline_course.start_date',
            'el_offline_course.end_date',
            'el_offline_course.status',
            'el_offline_course.lock_course',
            'el_offline_course.created_at',
            'el_offline_course.approved_step',
            'el_offline_course.created_by',
            'el_offline_course.updated_by',
            'c.name as subject_name',
        ]);
    //    $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_offline_course.training_program_id');
       $query->leftJoin('el_subject AS c', 'c.id', '=', 'el_offline_course.subject_id');
    //    $query->leftJoin('el_profile_view as pv', 'pv.user_id', '=', 'el_offline_course.created_by');



        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_offline_course.name', 'like', '%'. $search .'%');
                $subquery->orWhere('el_offline_course.code', 'like', '%'. $search .'%');
            });
        }
        if ($training_form_id){
            $query->where('el_offline_course.training_form_id', '=', $training_form_id);
        }
        if ($training_program_id){
            $query->where('el_offline_course.training_program_id', '=', $training_program_id);
        }

        if ($subject_id){
            $query->where('el_offline_course.subject_id', '=', $subject_id);
        }

        if ($start_date) {
            $query->where('el_offline_course.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('el_offline_course.start_date', '<=', date_convert($end_date, '23:59:59'));
        }
        if ($level_subject_id){
            $query->where('level_subject_id', '=', $level_subject_id);
        }

        $count = $query->count();
        $query->orderBy('el_offline_course.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.offline.edit', ['id' => $row->id]);
            $row->register_url = route('module.offline.register', ['id' => $row->id]);
            $row->register_deadline = get_date($row->register_deadline);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');

            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $training_partners = TrainingPartner::get();
        $areas = Area::where('status', '=', 1)->get();
        $user_invited = false;
        $titles = Titles::where('status', '=', 1)->get();
        $training_costs = TrainingCost::orderBy('type')->orderBy('id')->get();
        $type_costs = TypeCost::get();
        $student_costs = StudentCost::where('status','=',1)->get();
        $registers = OfflineStudentCost::getStudent($id);
        $total_actual_amount = OfflineStudentCost::getTotalActualAmount($id);
        $total_plan_amount = OfflineStudentCost::getTotalPlanAmount($id);
        $course_costs = OfflineCourseCost::where('course_id', '=', $id)->get();
        $condition = OfflineCondition::where('course_id', '=', $id)->first();
        $teachers = OfflineSchedule::getTeacher($id);
        //$teacher_types = TeacherType::get();
        $templates = RatingTemplate::get();
        $plan_app_template = PlanAppTemplate::get();
        $training_plan = TrainingPlan::get();
        $quizs = Quiz::where('status','=',1)->get();
        $province = Province::all();
        $certificate = Certificate::all();
        $qrcode_survey_after_course = null;
        $training_forms = TrainingForm::where('training_type_id',2)->get();
        $training_objects = TrainingObject::where('status',1)->get();

        $unit_manager_lv2 = UnitManager::query()
            ->from('el_unit_manager AS a')
            ->join('el_profile AS b', 'b.code', '=', 'a.user_code')
            ->join('el_unit AS c', 'c.code', '=', 'a.unit_code')
            ->where('c.level', '=', 2)
            ->where('b.user_id', '=', Auth::id())->pluck('c.id')->toArray();

        $units = Unit::where('status', '=', 1)->get();

        $corporations = Unit::where('level', '=', 1)->where('status', '=', 1)->get();
        if ($id) {
            $ratting_course = RattingCourse::where('course_id',$id)->where('type',2)->first();
            $model = OfflineCourse::find($id);
            if (!$model) abort(404);
            $training_location_course = TrainingLocation::find($model->training_location_id);


            if ($training_location_course){
                $district = District::query()->where('province_id','=',$training_location_course->province_id)->get();

                $training_location = TrainingLocation::where('province_id','=',$training_location_course->province_id)
                    ->where('district_id','=',$training_location_course->district_id)
                    ->where('status','=',1)
                    ->get();

                $model->training_location_province = $training_location_course->province_id;
                $model->training_location_district = $training_location_course->district_id;
            }else{
                $district=null;
                $training_location=null;
            }

            $page_title = $model->name;
            $subject = Subject::where('id', $model->subject_id)->first();
            $unit = explode(',', $model->unit_id);

            $training_program = TrainingProgram::where('id', $model->training_program_id)->first();
            $permission_save = userCan(['offline-course-create', 'offline-course-edit']);

            // $course_time = preg_replace("/[^0-9]/", '', $model->course_time);
            $course_time = $model->course_time;
            $course_time_unit = preg_replace("/[^a-z]/", '', $model->course_time_unit);

            $student_cost = function ($regid){
                return OfflineStudentCost::getTotalStudentCost($regid);
            };

            $exemption = function ($user_id, $course_id){
                return Indemnify::sumCommitAmount($user_id, $course_id);
            };

            /*if (Module::has('Promotion') && array_key_exists('Promotion', Module::allEnabled())) {
                $setting = PromotionCourseSetting::firstOrCreate(['course_id' => $id], ['type' => 1, 'method' => 1, 'status' => 0]);
            } else {
                $setting = new Collection();
            }*/

            $qrcode_survey_after_course = json_encode(['course'=>$id,'course_type'=>2,'survey'=>$model->template_id,'type'=>'survey_after_course']);

            $level_subject = LevelSubject::find($model->level_subject_id);

            !empty($model->title_join_id) ? $get_title_join_model_id = json_decode($model->title_join_id) : $get_title_join_model_id = [];
            !empty($model->title_recommend_id) ? $get_title_recommend_model_id = json_decode($model->title_recommend_id) : $get_title_recommend_model_id = [];
            !empty($model->document) ? $documents = json_decode($model->document) : $documents = [];
            !empty($model->training_area_id) ? $training_area = json_decode($model->training_area_id) : $training_area = [];
            !empty($model->training_object_id) ? $get_training_object_id = json_decode($model->training_object_id) : $get_training_object_id = [];
            !empty($model->training_unit) ? $training_unit = json_decode($model->training_unit) : $training_unit = [];
            !empty($model->training_partner_id) ? $training_partner = json_decode($model->training_partner_id) : $training_partner = [];

            $training_type=TrainingType::where('id', $model->training_type_id)->first();

            $teacher_type=TeacherType::where('id', $model->teacher_type_id)->first();

            $check_user_invited = OfflineInviteRegister::query()
                ->where('course_id', '=', $id)
                ->where('user_id', '=', Auth::id());
            if ($check_user_invited->exists()){
                $user_invited = true;
            }
            $this->saveIndemnify($id);
            $course_costs_id = OfflineCourseCost::where('course_id', '=', $id)->pluck('cost_id')->toArray();
            return view('offline::backend.offline.form', [
                'titles' => $titles,
                'model' => $model,
                'page_title' => $page_title,
                'subject' => $subject,
                'training_program' => $training_program,
                'training_costs' => $training_costs,
                'student_costs' => $student_costs,
                'registers' => $registers,
                'total_actual_amount' => $total_actual_amount,
                'total_plan_amount' => $total_plan_amount,
                'course_costs' => $course_costs,
                'condition' => $condition,
                'teachers' => $teachers,
               // 'teacher_types' => $teacher_types,
                'templates' => $templates,
                'plan_app_template'=>$plan_app_template,
                'province'=>$province,
                'district'=>$district,
                'training_location'=>$training_location,
                'training_plan' => $training_plan,
                'quizs'=>$quizs,
                'is_unit' => $model->unit_id,
                'permission_save' => $permission_save,
                'training_forms' => $training_forms,
                'student_cost' => $student_cost,
                'course_time' => $course_time,
                'course_time_unit' => $course_time_unit,
                'unit' => $unit,
                'exemption' => $exemption,
                'certificate' => $certificate,
                'setting' => null,
                'qrcode_survey_after_course' => $qrcode_survey_after_course,
                'units' => $units,
                'unit_manager_lv2' => $unit_manager_lv2,
                'level_subject' => $level_subject,
                'corporations' => $corporations,
                'training_area' => $training_area,
                'training_partner' => $training_partner,
                'get_title_join_model_id' => $get_title_join_model_id,
                'get_title_recommend_model_id' => $get_title_recommend_model_id,
                'get_training_object_id' => $get_training_object_id,
                'training_type' => $training_type,
                'training_objects' => $training_objects,
                'teacher_type' => $teacher_type,
                'user_invited' => $user_invited,
                'documents' => $documents,
                'areas' => $areas,
                'type_costs' => $type_costs,
                'course_costs_id' => $course_costs_id,
                'ratting_course' => $ratting_course,
                'training_unit' => $training_unit,
                'training_partners' =>$training_partners,
                'get_menu_child' => $get_menu_child,
                'name_url' => 'training_organizations',
            ]);
        }

        $model = new OfflineCourse();
        $page_title = trans('backend.add_new') ;
        $training_location = TrainingLocation::all();
        $permission_save = userCan(['offline-course-create', 'offline-course-edit']);
        return view('offline::backend.offline.form', [
            'titles' => $titles,
            'model' => $model,
            'page_title' => $page_title,
            'teachers' => $teachers,
            'plan_app_template'=>$plan_app_template,
            'province'=>$province,
            'district'=>null,
            'training_location'=>$training_location,
            'training_plan' => $training_plan,
            'quizs'=>$quizs,
            'is_unit' => $this->is_unit,
            'templates' => $templates,
            'permission_save' => $permission_save,
            'training_forms' => $training_forms,
            'course_time' => null,
            'course_time_unit' => null,
            'certificate' => $certificate,
            'qrcode_survey_after_course' => $qrcode_survey_after_course,
            'units' => $units,
            'unit_manager_lv2' => $unit_manager_lv2,
            'corporations' => $corporations,
            'user_invited' => $user_invited,
            'areas' => $areas,
            'type_costs' => $type_costs,
            'training_objects' => $training_objects,
            'training_partners' =>$training_partners,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'code' => 'required|unique:el_offline_course,code,'. $request->id,
            'name' => 'required',
            'category_id' => 'nullable|exists:el_course_categories,id',
            'in_plan' => 'nullable|exists:el_training_plan,id',
            'course_time' => 'nullable',
            'status' => 'nullable',
            'image' => 'nullable|string',
            'document' => "nullable|array|min:1",
            'document.*' => 'nullable|max:2048576',
            'num_lesson' => 'nullable',
            // 'action_plan' => 'required|in:0,1',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
            // 'plan_app_template'=>'required_if:action_plan,1|nullable|integer',
            // 'plan_app_day'=>'required_if:action_plan,1|nullable|integer|max:1000',
            'training_location_id'=>'nullable|integer',
            'training_unit'=>'nullable|max:256',
            'coefficient'=>'required_if:commit,1|nullable|integer|min:1|max:100',
            'training_form_id' => 'required',
        ], $request, OfflineCourse::getAttributeName());

        $document_upload = [];
        if (!empty($request->hidden_document)) {
            $document_upload = $request->hidden_document;
        }
        if ($request->hasfile('document')) {
            foreach ($request->file('document') as $file) {
                $folder_id = '';

                if (empty($folder_id)) {
                    $folder_id = null;
                }

                $type = 'file';
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $new_filename = Str::slug(basename($filename, "." . $extension)) . '-' . time() . '.' . $extension;

                $storage = \Storage::disk('upload');
                $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);

                if ($new_path) {
                    $warehouse = new Warehouse();
                    $warehouse->file_name = $file->getClientOriginalName();
                    $warehouse->file_type = $file->getMimeType();
                    $warehouse->file_path = $new_path;
                    $warehouse->file_size = $file->getSize();
                    $warehouse->extension = $file->getClientOriginalExtension();
                    $warehouse->source = 'upload';
                    $warehouse->type = $type;
                    $warehouse->created_by = \Auth::id();
                    $warehouse->updated_by = \Auth::id();
                    $warehouse->user_id = \Auth::id();
                    $warehouse->folder_id = $folder_id;
                    $warehouse->save();
                    $document_upload[] = $new_path;
                }
            }
        }

        if(!$request->has('commit')){
            $request->merge([
                'commit' => "0",
            ]);
        }
        $course_time_unit = $request->course_time_unit;
        $unit_id = $request->post('unit_id');

        if ($request->post('id')){
            $check_schedule = OfflineSchedule::where('course_id', '=', $request->post('id'))->exists();
            if ($check_schedule){
                $min_lesson_date = OfflineSchedule::where('course_id', '=', $request->post('id'))->min('lesson_date');
                $max_lesson_date = OfflineSchedule::where('course_id', '=', $request->post('id'))->max('lesson_date');

                if ( get_date($request->input('start_date'), 'Y-m-d 00:00:00') > get_date($min_lesson_date, 'Y-m-d H:i:s')){
                    json_message('Đã có lịch học. Thời gian bắt đầu phải trước lịch học', 'error');
                }
                if ( get_date($request->input('end_date'), 'Y-m-d 23:59:59') < get_date($max_lesson_date, 'Y-m-d H:i:s')){
                    json_message('Đã có lịch học. Thời gian kết thúc phải sau lịch học', 'error');
                }
            }
        }

        if(date_convert($request->input('start_date')) > date_convert($request->input('end_date'), '23:59:59')){
            json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
        }

        if ($request->input('register_deadline')){
            if(date_convert($request->input('register_deadline')) > date_convert($request->input('end_date'), '23:59:59')){
                json_message('Hạn đăng ký phải trước Ngày kết thúc', 'error');
            }
        }

        $subject = Subject::find($request->subject_id);

        $model = OfflineCourse::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $model->has_cert = $request->input('has_cert') ? $request->input('has_cert') : 0;
        $date_original = null;
        if (empty($model->id)) {
            $model->created_by = Auth::id();
            $model->status = 2;
        }else{
            $date_original = OfflineCourse::where(['id' => $request->post('id')])->value('start_date');
        }

        $model->updated_by = Auth::id();

        $model->training_type_id = 2;
        $model->register_deadline = $request->input('register_deadline') ? date_convert($request->input('register_deadline'), '23:59:59') : null;
        $model->start_date = date_convert($request->input('start_date'));
        $model->end_date = date_convert($request->input('end_date'), '23:59:59');
        $model->commit_date = date_convert($request->input('commit_date'), '00:00:00');
        if($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image);
        }
        // $model->document = path_upload($model->document);
        $model->document = is_array($document_upload) ? json_encode($document_upload) : '';
        $model->unit_id = is_array($unit_id) ? implode(',', $unit_id) : null;
        $model->course_time = $request->input('course_time');
        $model->course_time_unit = $course_time_unit;
        $model->level_subject_id = $subject->level_subject_id;
        $model->rating_end_date = $request->input('rating_end_date') ? date_convert($request->input('rating_end_date'), '23:59:59') : null;
        $model->training_area_id = is_array($request->training_area_id) ? json_encode($request->training_area_id) : '';
        $model->title_join_id = is_array($request->title_join_id) ? json_encode($request->title_join_id) : '';
        $model->title_recommend_id = is_array($request->title_recommend_id) ? json_encode($request->title_recommend_id) : '';
        $model->training_object_id = is_array($request->training_object_id) ? json_encode($request->training_object_id) : '';
        $model->training_unit = is_array($request->training_unit) ? json_encode($request->training_unit) : '';
        $model->training_partner_id = is_array($request->training_partner_id) ? json_encode($request->training_partner_id) : '';

        if ($model->save()) {
            /********update thống kê khóa học **********/
            if (empty($request->id))
                CourseStatistic::update_course_insert_statistic($model->id,2);
            else
                CourseStatistic::update_course_update_statistic($model->id,2,$date_original);
            /*********************end***********************/
            /*update khóa học kỳ thi */
            if ($request->id){
                Quiz::where('course_id','=',$model->id)->where('course_type','=',2)->update(['course_id'=>0,'course_type'=>0]);

                $history_edit = new OnlineHistoryEdit();
                $history_edit->course_id = $model->id;
                $history_edit->user_id = Auth::id();
                $history_edit->tab_edit = 'Sửa thông tin khóa học';
                $history_edit->ip_address = \request()->ip();
                $history_edit->type = 2;
                $history_edit->save();
            }else{
                $history_edit = new OnlineHistoryEdit();
                $history_edit->course_id = $model->id;
                $history_edit->user_id = Auth::id();
                $history_edit->tab_edit = 'Thêm thông tin khóa học';
                $history_edit->ip_address = \request()->ip();
                $history_edit->type = 2;
                $history_edit->save();
            }
            if ($request->quiz_id)
            Quiz::where('id','=',$request->quiz_id)->update(['course_id'=>$model->id,'course_type'=>2]);

            /**************************/
            //$redirect = $model->unit_id > 0 ? route('module.training_unit.offline.edit', ['id' => $model->id]): route('module
            //.offline.edit', ['id' => $model->id]);
            $redirect = route('module.offline.edit', ['id' => $model->id]);

            $resgiters = OfflineRegister::where('course_id', '=', $model->id)
                ->where('status', '=', 1)
                ->get();
            foreach ($resgiters as $resgiter){
                Indemnify::where('user_id', '=', $resgiter->user_id)
                    ->where('course_id', '=',  $model->id)
                    ->update([
                        'coefficient' => $model->coefficient,
                    ]);

                $indem = Indemnify::checkExists($resgiter->user_id, $model->id);
                if ($indem && $indem->commit_amount){
                    $indem->commit_amount = ($indem->course_cost * $indem->coefficient) + $indem->cost_student;
                    $indem->save();
                }
            }

            $report_11 = ReportNewExportBC11::query()->where('course_id', '=', $model->id)->where('course_type', '=', 2);
            if ($request->id && $report_11->exists()){
                $training_form = TrainingType::query()->find($model->training_type_id);
                $training_location = TrainingLocation::query()->find($model->training_location_id);
                $subject = Subject::query()->find($model->subject_id);
                $course_time = preg_replace("/[^0-9]/", '', $model->course_time);
                $total_register = OfflineRegister::whereCourseId($model->id)->count();

                $report_11->update([
                    'course_code' => @$model->code,
                    'course_name' => @$model->name,
                    'subject_id' => @$subject->id,
                    'subject_name' => @$subject->name,
                    'training_form_id' => @$training_form->id,
                    'training_form_name' => @$training_form->name,
                    'course_time' => $course_time,
                    'start_date' => @$model->start_date,
                    'end_date' => @$model->end_date,
                    'total_register' => $total_register,
                    'training_location_id' => @$training_location->id,
                    'training_location_name' => @$training_location->name,
                ]);
            }

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
                'redirect' => $redirect,
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }
    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $course = OfflineCourse::find($id);
            $result = OfflineResult::where('course_id', '=', $id);
            if ($result->exists() || $course->status == 1){
                continue;
            }

            ReportNewExportBC11::query()
                ->where('course_id', '=', $id)
                ->where('course_type', '=', 2)
                ->delete();
            ReportNewExportBC08::query()
                ->where('course_id', '=', $id)
                ->delete();
            ReportNewExportBC05::query()
                ->where('course_id', '=', $id)
                ->where('course_type', '=', 2)
                ->delete();

            CourseStatistic::update_course_delete_statistic(2, $course->start_date);

            Quiz::where('course_id','=', $id)
                ->where('course_type', '=', 2)
                ->update(['course_id'=>0,'course_type'=>0]);

            if($course->delete()){
                $offlineCourse = OfflineCourse::find($id);
                $data = OfflineRegister::select('id','user_id','course_id')->with('user:user_id,code,firstname,lastname,gender,email')->where(['course_id'=>$id,'status'=>1])->get();
                foreach ($data as $item) {
                    $signature = getMailSignature($item->user_id);
                    $params = [
                        'gender' => $item->user->gender=='1'?'Anh':'Chị',
                        'full_name' => $item->user->full_name,
                        'course_code' => $offlineCourse->code,
                        'course_name' => $offlineCourse->name,
                        'signature' => $signature
                    ];
                    $user_id = [$item->user_id];
                    $this->saveEmailDeletedCourse($params,$user_id,$item->id);
                }
            }
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxGetSubject(Request $request){
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
        ], $request, [
            'training_program_id' => 'Chuong trinh dao tao',
        ]);

        $training_program_id = $request->training_program_id;
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
        $check_user_invited = OfflineInviteRegister::query()->where('user_id', '=', Auth::id());
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
                $model = OfflineCourse::findOrFail($id);
                $model->isopen = $status;
                $model->save();
            }
        } else {
            $model = OfflineCourse::findOrFail($ids);
            $model->isopen = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' =>trans('lageneral.successful_save'),
        ]);
    }

    public function saveObject($course_id, Request $request){
        $this->validateRequest([
            'type' => 'required|in:1,2',
        ], $request, OfflineObject::getAttributeName());

        $object_type = $request->post('object_type', '');
        $titles = $request->post('title', '') ? explode(',', $request->post('title', '')) : '';
        $units = $request->post('unit', '');
        $type = $request->input('type');

        if (!$units && $titles) {
            json_message('Chưa chọn đơn vị', 'error');
        }

        if ($units && $titles){
            if (count($units) > 1){
                json_message('Không thể chọn quá nhiều đơn vị và chức danh', 'error');
            }else{
                $unit = Unit::find($units[0]);
                foreach ($titles as $item) {
                    if (!Titles::where('id', '=', $item)->exists()) {
                        continue;
                    }

                    if (!Unit::where('id', '=', $unit->id)->exists()) {
                        continue;
                    }

                    if (OfflineObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->whereNull('title_id')->exists()) {
                        continue;
                    }

                    if (OfflineObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->where('title_id', '=', $item)->exists()) {
                        continue;
                    }

                    if (OfflineObject::where('course_id', '=', $course_id)->whereNull('unit_id')->where('title_id', '=', $item)->exists()) {
                        OfflineObject::where('course_id', '=', $course_id)
                            ->whereNull('unit_id')
                            ->where('title_id', '=', $item)
                            ->update([
                                'unit_id' => $unit->id,
                                'unit_level' => $unit->level,
                            ]);
                    }else{
                        $model = new OfflineObject();
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

                if (OfflineObject::where('course_id', '=', $course_id)
                    ->where('unit_id', '=', $item)
                    ->exists()) {
                    continue;
                }

                if (!Unit::where('id', '=', $item)->exists()) {
                    continue;
                }

                $unit = Unit::find($item);

                $model = new OfflineObject();
                $model->unit_id = $item;
                $model->unit_level = $unit->level;
                $model->type = $type;
                $model->course_id = $course_id;
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
                $model->fill($request->all());
                $model->save();
            }
        }

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = Auth::id();
        $history_edit->tab_edit = 'Thêm đối tượng tham gia khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        return \response()->json([
            'status' => 'success',
            'message' => 'Thêm đối tượng thành công',
        ]);
    }

    public function getObject($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineObject::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
            'c.name AS unit_name',
            'd.name AS unit_manager',
        ]);
        $query->from('el_offline_object AS a')
            ->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id')
            ->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id')
            ->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code')
            ->where('a.course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function removeObject($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Đối tượng',
        ]);

        $item = $request->input('ids');
        OfflineObject::destroy($item);

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = Auth::id();
        $history_edit->tab_edit = 'Xoá đối tượng tham gia khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveCost($course_id, Request $request){
        $find = [',', ';', '.'];

        $cost_ids = $request->id;
        $plan_amounts = str_replace($find, '', $request->plan_amount);
        $actual_amounts = str_replace($find, '', $request->actual_amount);
        $notes = $request->note;
        // dd($cost_ids);
        foreach($cost_ids as $key => $cost_id){
            if (empty($cost_id) || $cost_id <= 0) {
                continue;
            }
            $model = OfflineCourseCost::firstOrNew(['course_id' => $course_id, 'cost_id' => $cost_id]);
            $model->cost_id = $cost_id;
            $model->plan_amount = $plan_amounts[$key] ? $plan_amounts[$key] : 0;
            $model->actual_amount = $actual_amounts[$key] ? $actual_amounts[$key] : 0;
            $model->notes = $notes[$key] ? $notes[$key] : '';
            $model->course_id = $course_id;
            $model->save();
        }
        /*****update sum chi phí khóa học ***/
        $cost_class = OfflineCourseCost::sumActualAmount($course_id);
        $resgiters = OfflineRegister::where('course_id', '=', $course_id)
            ->where('status', '=', 1)
            ->get();

        if (count($resgiters) > 0){
            $course_cost = $cost_class/$resgiters->count();

            $course = OfflineCourse::find($course_id);

            foreach ($resgiters as $resgiter){
                Indemnify::where('user_id', '=', $resgiter->user_id)
                    ->where('course_id', '=', $course_id)
                    ->update([
                        'course_cost' => $course_cost,
                    ]);

                $indem = Indemnify::checkExists($resgiter->user_id, $course->id);

                if ($indem && $indem->commit_amount){
                    $indem->commit_amount = ($indem->course_cost * $indem->coefficient) + $indem->cost_student;
                    $commit_date = CommitMonth::getMonth($indem->commit_amount);
                    $indem->commit_date = $commit_date * 30;
                    $indem->save();
                }
            }
        }

        OfflineCourse::where('id', '=', $course_id)
            ->update([
                'cost_class' => $cost_class
            ]);
//        OfflineCourseView::where('id',$course_id)->update(['cost_class' => $cost_class]);
        /******update course view******/
        $this->updateCostCourseView($course_id);
        /**************/
        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = Auth::id();
        $history_edit->tab_edit = 'Thêm chi phí đào tạo';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Thêm chi phí đào tạo thành công',
        ]);
    }
    private function updateCostCourseView($course_id){
        $data = OfflineCourseCost::where('course_id',$course_id)->select(DB::raw('sum(plan_amount) as plan_amount, sum(actual_amount) as actual_amount'))->first();
        OfflineCourseView::where(['id'=>$course_id])->update(['plan_amount'=>$data->plan_amount,'actual_amount'=>$data->actual_amount]);
        CourseView::where(['course_id'=>$course_id,'course_type'=>2])->update(['plan_amount'=>$data->plan_amount,'actual_amount'=>$data->actual_amount]);
    }
    public function saveStudentCost($course_id, Request $request){
        $register_id = $request->regid;
        $cost_ids = $request->cost_id;
        $costs = str_replace(',','',$request->cost);
        $notes = $request->note;

        foreach($cost_ids as $key => $cost_id){

            if (empty($cost_id) || $cost_id <= 0) {
                continue;
            }

            if(OfflineStudentCost::checkExists($register_id, $cost_id)){
                OfflineStudentCost::where('register_id', '=', $register_id)
                ->where('cost_id', '=', $cost_id)
                ->update([
                    'cost' => (float) $costs[$key],
                    'note' => $notes[$key],
                ]);
                continue;
            }

            $model = new OfflineStudentCost();
            $model->cost_id = $cost_id;
            $model->cost = (float) $costs[$key];
            $model->note = $notes[$key];
            $model->register_id = $register_id;

            $model->save();
        }
        /**update chi phí học viên vào table cam kết bồi hoàn*/
        $cost_studet = OfflineStudentCost::getTotalStudentCost($register_id);
        $user_id = OfflineRegister::find($register_id)->user_id;

        if(Indemnify::checkExists($user_id, $course_id)){
            Indemnify::where('user_id', '=', $user_id)
                ->where('course_id', '=', $course_id)
                ->update([
                    'cost_student' => $cost_studet,
                ]);
            $indem = Indemnify::checkExists($user_id, $course_id);
            if ($indem && $indem->commit_amount){
                $indem->commit_amount = ($indem->course_cost * $indem->coefficient) + $indem->cost_student;
                $commit_date = CommitMonth::getMonth($indem->commit_amount);
                $indem->commit_date = $commit_date * 30;
                $indem->save();
            }

        }else{
            $model = new Indemnify();
            $model->cost_student = $cost_studet;
            $model->course_id = $course_id;
            $model->user_id = $user_id;
            $model->save();
        }
        /**********/

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = Auth::id();
        $history_edit->tab_edit = 'Thêm chi phí học viên';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();


        json_result([
            'status' => 'success',
            'message' => 'Thêm chi phí học viên thành công',
        ]);
    }
    private function saveIndemnify($course_id){
        $totalUser = OfflineRegister::where(['course_id'=>$course_id,'status'=>1])->count("id");
        $users = OfflineRegisterView::leftJoin('el_titles','el_titles.id','=','el_offline_register_view.title_id')
            ->where(['course_id'=>$course_id,'el_offline_register_view.status'=>1])
            ->whereNotExists(function (Builder $subquery){
            $subquery->select('user_id')->from('el_indemnify')
                ->whereColumn('user_id','=','el_offline_register_view.user_id')
                ->whereColumn('course_id','=','el_offline_register_view.course_id');
        })
            ->get(['el_offline_register_view.id','el_offline_register_view.user_id','el_offline_register_view.title_id','el_titles.group']);

        $totalAmount = OfflineStudentCost::getTotalActualAmount($course_id);
        $commit_amount = $course_cost = $totalUser>0?$totalAmount/$totalUser:0;
        foreach($users as $key => $user){

            $dayCommit = CommitMonth::getDayCommit($user->group,$course_cost);
            Indemnify::updateOrCreate(['user_id' => $user->user_id,'course_id' => $course_id],
                [
                    'commit_date' => $dayCommit? $dayCommit: null,
                    'course_cost' => $course_cost,
                    'exemption_amount' => 0,
                    'coefficient' => 1,
                    'commit_amount' => $commit_amount,
                ]);
        }
    }
    public function saveCommitDate($course_id, Request $request){
        $register_id = $request->id;
        $user_id = $request->user_id;
        $course_cost = str_replace(',','', $request->course_cost);
        $commitDate = $request->commit_date;
        $coefficient = $request->coefficient;
        $calculator = $request->calculator;
        $exemption_amount = str_replace(',', '', $request->exemption_amount);
        $month = $request->month;
        foreach($register_id as $key => $value){
            $indem = Indemnify::checkExists($user_id[$key], $course_id);
            $title_id = Profile::find($user_id[$key])->title_id;
            $titleRank = Titles::find($title_id)->group;dd($calculator);
            if ($calculator[$key]=='-' && ((double) $exemption_amount[$key] > ($course_cost[$key] * $coefficient[$key] + ($indem->cost_student ? $indem->cost_student : 0))) )
            {
                json_message('Số tiền miễn giảm không thể lớn hơn số tiền cam kết', 'error');
            }

            if (empty($value) || $value <= 0) {
                continue;
            }

            if($indem){
                Indemnify::updateOrCreate(['user_id' => $user_id[$key],'course_id' => $course_id],
                [
                    'exemption_amount' => (double)$exemption_amount[$key],
                    'course_cost' => $course_cost[$key],
                    'coefficient' => $coefficient[$key],
                    'calculator' => $exemption_amount[$key]>0? $calculator[$key]:null,
                ]);
                try {
                    $model = Indemnify::checkExists($user_id[$key], $course_id);
                    if ($model->calculator == '+') {
                        $model->commit_amount = (float)$model->course_cost * 1 + (float)$model->cost_student + (float)$model->exemption_amount;
                    } elseif ($model->calculator == '-')
                        $model->commit_amount = (float)$model->course_cost * 1 + (float)$model->cost_student - (float)$model->exemption_amount;
                    else
                        $model->commit_amount = ((float)$model->course_cost * 1) + (float)$model->cost_student;
                    $commit_date = CommitMonth::getDayCommit($titleRank,$model->commit_amount);
                    $model->commit_date = $commitDate[$key]>0?$commitDate[$key]: $commit_date;
                    $model->save();
                }catch (\Exception $e){
                    dd($e,$model->calculator, $model->course_cost,$model->cost_student);
                }
                continue;
            }

//            $model = new Indemnify();
//            $model->commit_date = (int) $commit_date[$key] ? $commit_date[$key] : ($month ? $month : null);
//            $model->exemption_amount = $exemption_amount[$key];
//            $model->course_id = $course_id;
//            $model->course_cost = $course_cost[$key];
//            $model->coefficient = $coefficient[$key];
//            $model->user_id = $user_id[$key];
//            $model->commit_amount = $course_cost[$key] * $coefficient[$key];
//            $model->calculator = $exemption_amount[$key]>0? $calculator[$key]:null;
//            $model->save();
        }

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = Auth::id();
        $history_edit->tab_edit = 'Thêm số tháng cam kết';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
        ]);
    }

    public function getModalStudentCost($course_id, Request $request) {
        $this->validateRequest([
            'regid' => 'required',
        ], $request);
        $get_commit_amount = str_replace(',','',$request->get_commit_amount);
        $model = OfflineCourse::find($course_id);
        $student_costs = StudentCost::where('status','=',1)->get();
        $register = OfflineStudentCost::getRegister($request->regid);
        $register_cost = OfflineStudentCost::where('register_id', '=', $register->id)->get();
        $get_total_student_cost = OfflineStudentCost::getTotalStudentCost($register->id);
        $total_student_cost = !empty($get_total_student_cost) ? ($get_total_student_cost + $get_commit_amount) : 0;
        // dd(ceil($total_student_cost));
        return view('offline::modal.student_cost', [
            'model' => $model,
            'course_id' => $course_id,
            'regid' => $request->regid,
            'student_costs' => $student_costs,
            'register' => $register,
            'register_cost' => $register_cost,
            'total_student_cost' => $total_student_cost
        ]);
    }

    public function saveCondition($course_id, Request $request){
        $this->validateRequest([
            'ratio' => 'nullable|numeric|min:1|max:100',
            'minscore' => 'nullable|numeric|min:1',
        ], $request, OfflineCondition::getAttributeName());

        $offlineCondition = OfflineCondition::firstOrNew(['course_id' => $course_id]);
        $offlineCondition->course_id = $course_id;
        $offlineCondition->ratio = $request->input('ratio');
        $offlineCondition->minscore = $request->input('minscore');
        $offlineCondition->survey = $request->survey;
        $offlineCondition->certificate = $request->certificate;
        $offlineCondition->save();

        if(OfflineCondition::checkExists($course_id)){
            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = Auth::id();
            $history_edit->tab_edit = 'Cập nhật điều kiện hoàn thành khóa học';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 2;
            $history_edit->save();

        }else{
            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = Auth::id();
            $history_edit->tab_edit = 'Thêm điều kiện hoàn thành khóa học';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 2;
            $history_edit->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function saveScheduleParent($course_id, Request $request){
        $this->validateRequest([
            'start_time' => 'required',
            'end_time' => 'required',
            'lesson_date' => 'required|date_format:d/m/Y',
        ], $request, OfflineScheduleParent::getAttributeName());

        $id = $request->id;
        $lesson_date = date_convert($request->input('lesson_date'));
        $start_time = $request->input('start_time') . ':00';
        $end_time = $request->input('end_time') . ':00';

        $check = OfflineCourse::where('id', '=', $course_id)
                ->where('start_date', '>', date_convert($request->lesson_date, $start_time))
                ->where('end_date', '<', date_convert($request->lesson_date, $end_time))
                ->exists();
        if ($check){
            json_message('Lịch học không nằm trong thời gian học', 'error');
        }

        $check_exist1 = OfflineScheduleParent::where('id', '!=', $id)
            ->where('lesson_date', '=', $lesson_date)
            ->where('start_time', '<=', $start_time)
            ->where('end_time', '>=', $start_time)
            ->where('course_id', '=', $course_id)
            ->exists();

        if ($check_exist1){
            json_message('Giờ học đã tồn tại', 'error');
        }

        $check_exist2 = OfflineScheduleParent::where('id', '!=', $id)
            ->where('lesson_date', '=', $lesson_date)
            ->where('start_time', '<=', $end_time)
            ->where('end_time', '>=', $end_time)
            ->where('course_id', '=', $course_id)
            ->exists();

        if ($check_exist2){
            json_message('Giờ học đã tồn tại', 'error');
        }

        if(get_date($start_time, 'H') >= get_date($end_time, 'H') && get_date($start_time, 'i') >= get_date($end_time, 'i')){
            json_message('Giờ kết thúc phải sau Giờ bắt đầu', 'error');
        }

        $model = OfflineScheduleParent::firstOrNew(['id' => $id]);
        $model->start_time = $start_time;
        $model->end_time = $end_time;
        $model->lesson_date = $lesson_date;
        $model->course_id = $course_id;
        if (empty($id)){
            $model->created_by = Auth::id();
        }
        $model->updated_by = Auth::id();

        if ($model->save()) {

            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = Auth::id();
            $history_edit->tab_edit = empty($id) ? 'Thêm lịch học' : 'Sửa lịch học';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 2;
            $history_edit->save();

            json_result([
                'status' => 'success',
                'message' => 'Thêm thành công',
            ]);
        }
    }

    public function getScheduleParent($course_id, Request $request){
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineScheduleParent::query();
        $query->select(['a.*']);
        $query->from('el_offline_schedule_parent AS a');
        $query->where('a.course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.lesson_date', 'ASC');
        $query->orderBy('a.start_time', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->start_time = get_date($row->start_time, 'H:i');
            $row->end_time = get_date($row->end_time, 'H:i');
            $row->lesson_date = get_date($row->lesson_date, 'd/m/Y');
            $row->created_by = Profile::fullname($row->created_by);
            $row->updated_by = Profile::fullname($row->updated_by);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeScheduleParent($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request);

        $item = $request->input('ids');
        foreach ($item as $value){
            OfflineAttendance::whereCourseId($course_id)
                ->whereIn('schedule_id', function ($sub) use ($course_id, $value){
                    $sub->select(['id'])
                        ->from('el_offline_schedule')
                        ->where('course_id', '=', $course_id)
                        ->where('schedule_parent_id', '=', $value)
                        ->pluck('id')
                        ->toArray();
                })->delete();

            OfflineSchedule::query()
                ->where('course_id', '=', $course_id)
                ->where('schedule_parent_id', '=', $value)
                ->delete();
        }

        OfflineScheduleParent::destroy($item);

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = Auth::id();
        $history_edit->tab_edit = 'Xoá lịch học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function getModalSchedule($course_id, Request $request) {
        $schedule_parent_id = $request->schedule_parent_id;
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $lesson_date = $request->lesson_date;
        $model = OfflineCourse::find($course_id);
        $teachers = OfflineSchedule::getTeacher($course_id);

        return view('offline::backend.offline.form.schedule', [
            'model' => $model,
            'course_id' => $course_id,
            'schedule_parent_id' => $schedule_parent_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'lesson_date' => $lesson_date,
            'teachers' => $teachers
        ]);
    }

    public function saveSchedule($course_id, Request $request){
        $this->validateRequest([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'teacher_id' => 'required|exists:el_training_teacher,id',
            'cost_teacher' => 'nullable|min:0',
            'teacher_type' => 'nullable|min:0',
        ], $request, OfflineSchedule::getAttributeName());

        $schedule_parent_id = $request->schedule_parent_id;
        $teacher_id = $request->teacher_id;
        $teacher_type = $request->teacher_type;
        $cost_teacher = $request->cost_teacher;
        $lesson_date = date_convert($request->lesson_date);
        $start_time = $request->start_time . ':00';
        $end_time = $request->end_time . ':00';

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $hours = $end->diffInHours($start);

        $schedule_parent = OfflineScheduleParent::find($schedule_parent_id);

        if (get_date($start_time,'H:i') < get_date($schedule_parent->start_time, 'H:i')){
            json_message('Giờ học không được trước Giờ bắt đầu', 'error');
        }

        if (get_date($end_time, 'H:i') > get_date($schedule_parent->end_time, 'H:i')){
            json_message('Giờ học không được sau Giờ kết thúc', 'error');
        }

        if(get_date($start_time, 'H:i') >= get_date($end_time, 'H:i') ){
            json_message('Giờ kết thúc phải sau Giờ bắt đầu', 'error');
        }

        // $check_exist1 = OfflineSchedule::where('schedule_parent_id', '=', $schedule_parent_id)
        //     ->where('start_time', '<=', $start_time)
        //     ->where('end_time', '>=', $start_time)
        //     ->where('course_id', '=', $course_id)
        //     ->exists();

        // if ($check_exist1){
        //     json_message('Giờ học đã tồn tại', 'error');
        // }

        // $check_exist2 = OfflineSchedule::where('schedule_parent_id', '=', $schedule_parent_id)
        //     ->where('start_time', '<=', $end_time)
        //     ->where('end_time', '>=', $end_time)
        //     ->where('course_id', '=', $course_id)
        //     ->exists();

        // if ($check_exist2){
        //     json_message('Giờ học đã tồn tại', 'error');
        // }

        $model = new OfflineSchedule();
        $model->schedule_parent_id = $schedule_parent_id;
        $model->start_time = $start_time;
        $model->end_time = $end_time;
        $model->lesson_date = $lesson_date;
        $model->teacher_main_id = ($teacher_type == 1 ? $teacher_id : null);
        $model->teach_id = ($teacher_type == 2 ? $teacher_id : null);
        $model->cost_teacher_main = ($teacher_type == 1 ? ((int)$cost_teacher * (int)$hours) : null);
        $model->cost_teach_type = ($teacher_type == 2 ? ((int)$cost_teacher * (int)$hours) : null);
        $model->total_lessons = 1;
        $model->course_id = $course_id;

        if ($model->save()) {
            $this->updateScheduleCourseView($course_id);
            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = Auth::id();
            $history_edit->tab_edit = 'Thêm lịch học';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 2;
            $history_edit->save();

            $this->updateReportNewBC11($model);

            json_result([
                'status' => 'success',
                'message' => 'Thêm thành công',
            ]);
        }
    }
    private function updateScheduleCourseView($course_id){
        $schedules = OfflineSchedule::where('course_id',$course_id)->select('total_lessons','start_time','end_time','lesson_date')->get();
        $strSchedule='';
        foreach ($schedules as $index => $schedule) {
            $strSchedule.= 'Buổi '.$schedule->total_lessons.' ('. get_date($schedule->start_time,'H:i').' '.get_date($schedule->end_time,'H:i').' - '.get_date($schedule->lesson_date, 'd/m/Y').')'.PHP_EOL;
        }
        OfflineCourseView::where(['id'=>$course_id])->update(['schedules'=>$strSchedule]);
        CourseView::where(['course_id'=>$course_id,'course_type'=>2])->update(['schedules'=>$strSchedule]);
    }
    public function getSchedule($course_id, Request $request){
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $schedule_parent_id = $request->schedule_parent_id;

        $query = OfflineSchedule::query();
        $query->select(['a.*', 'b.name as main_name', 'c.name as teach_name']);
        $query->from('el_offline_schedule AS a');
        $query->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_main_id');
        $query->leftJoin('el_training_teacher AS c', 'c.id', '=', 'a.teach_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.schedule_parent_id', '=', $schedule_parent_id);

        $count = $query->count();
        $query->orderBy('a.lesson_date', 'ASC');
        $query->orderBy('a.start_time', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->start_time = get_date($row->start_time, 'H:i');
            $row->end_time = get_date($row->end_time, 'H:i');
            if ($row->main_name){
                $row->teacher_name = $row->main_name;
                $cost_teacher = $row->cost_teacher_main;
                $row->teacher_type = 'Giảng viên chính';
            }else{
                $row->teacher_name = $row->teach_name;
                $cost_teacher = $row->cost_teach_type;
                $row->teacher_type = 'Trợ giảng';
            }
            $row->cost_teacher = number_format($cost_teacher, 0). ' VNĐ';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeSchedule($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request);

        $item = $request->input('ids');

        OfflineAttendance::whereCourseId($course_id)->whereIn('schedule_id', $item)->delete();

        OfflineSchedule::destroy($item);

        ReportNewExportBC11::query()->whereIn('schedule_id', $item)->delete();

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = Auth::id();
        $history_edit->tab_edit = 'Xoá lịch học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();
        $this->updateScheduleCourseView($course_id);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxGetCourseCode(Request $request){
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

        $subject_id = $request->subject_id;
        $id = $request->id;
        $subject = Subject::find($subject_id);
        $courses = OfflineCourse::where('subject_id', '=', $subject->id)->get();
        $level_subject = LevelSubject::find($subject->level_subject_id);

        $count_course = count($courses);
        $check_count_course = '';
        for ($i = 1; $i <= $count_course; $i++) {
            $count = '00'.$i;
            $get_course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
            $check_subject_course_code = OfflineCourse::where('code',$get_course_code)->first();
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
            $get_course_code_subject = OfflineCourse::find($id);
            if($get_course_code_subject->subject_id == $subject_id ) {
                $course_code = $get_course_code_subject->code;
            }
        }

        json_result([
            'id' => count($courses),
            'course_code' => $course_code,
            'description' => $subject->description,
            'content' => $subject->content,
            'level_subject_name' => @$level_subject->name,
        ]);
    }

    public function filterLocation(Request $request)
    {
        if ($request->province_id){
            $district = District::query()->where('province_id','=',$request->province_id)->get();
            echo json_result($district);
        }
        exit();
    }

    public function filterTrainingLocation(Request $request)
    {
        $query = TrainingLocation::query();
        if ($request->province_id){
            $query->where('province_id','=',$request->province_id);
        }
        if ($request->district_id){
            $query->where('district_id','=',$request->district_id);
        }
        echo json_result($query->get());
        exit();

    }

    public function approve(Request $request) {
        $user_invited = null;
        $check_user_invited = OfflineInviteRegister::query()->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }

        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            if ($user_invited && in_array($id, $user_invited)){
                continue;
            }
            (new ApprovedModelTracking())->updateApprovedTracking($id,$status);

//            $model = OfflineCourse::findOrFail($id);
//            $model->status = $status;
//            $model->lock_course= 1;
//            $model->save();
            $this->updateEmailCourseObject($id);
        }

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }

    public function lockCourse(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);
        $user_invited = null;
        $check_user_invited = OfflineInviteRegister::query()->where('user_id', '=', Auth::id());
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
                $model = OfflineCourse::findOrFail($id);
                $model->lock_course = $status;
                $model->save();
            }
        } else {
            $model = OfflineCourse::findOrFail($ids);
            $model->lock_course = $status;
            $model->save();
        }
 
        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function sendMailApprove(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, ['ids' => 'Khóa học']);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $course = OfflineCourse::find($id);
            $users = [];
            if ($course->status != 1) {
                $automail = new Automail();
                $automail->template_code = 'approve_course';
                $automail->params = [
                    'code' => $course->code,
                    'name' => $course->name,
                    'start_date' => $course->start_date,
                    'end_date' => $course->end_date
                ];
                $automail->users = $users;
                $automail->check_exists = true;
                $automail->check_exists_status = 0;
                $automail->object_id = $course->id;
                $automail->object_type = 'approve_offline';
                $automail->addToAutomail();
            }
        }

        json_message('Gửi mail thành công');
    }
    public function updateEmailCourseObject($course_id)
    {
//        $data = OfflineCourseComplete::with('users:id,user_id,email,firstname,lastname,gender')->where('course_id',$course_id)->get()->pluck('users')->flatten();
//        dd($data->toArray());
//        return false;
        $course = OfflineCourse::find($course_id);
        // theo đơn vị
        $objects_unit = OfflineObject::select('id','course_id','unit_id','title_id')->where('course_id',$course_id)
            ->with('unit:id,code',
                'unit.profiles:unit_code,id,code,user_id,email,firstname,lastname,gender')->has('unit')->get();
        foreach ($objects_unit as $object) {
            foreach ($object->unit['profiles'] as $profile) {
                $signature = getMailSignature($profile->user_id);
                $params = [
                    'gender' => $profile->gender=='1'?'Anh':'Chị',
                    'full_name' => $profile->full_name,
                    'course_code' => $course->code,
                    'course_name' => $course->name,
                    'course_type' => 'Offline',
                    'start_date' => get_date($course->start_date),
                    'end_date' => get_date($course->end_date),
                    'training_location' => 'Elearning',
                    'url' => route('module.offline.detail', ['id' => $course->id]),
                    'signature' => $signature
                ];
                $user_id = [$profile->user_id];
                $this->saveEmailCourseObject($params,$user_id,$course->id);
            }
        }
        //theo chức danh
        $objects = OfflineObject::select('id','course_id','unit_id','title_id')->where('course_id',$course_id)
            ->with('titles:id,code',
                'titles.profiles:title_code,id,code,user_id,email,firstname,lastname,gender')->whereNotNull('title_id')->get();
        foreach ($objects as $object) {
            foreach ($object->titles as $profiles) {
                foreach ($profiles->profiles as $profile){
                    $signature = getMailSignature($profile->user_id);
                    $params = [
                        'gender' => $profile->gender=='1'?'Anh':'Chị',
                        'full_name' => $profile->full_name,
                        'course_code' => $course->code,
                        'course_name' => $course->name,
                        'course_type' => 'Offline',
                        'start_date' => get_date($course->start_date),
                        'end_date' => get_date($course->end_date),
                        'training_location' => 'Elearning',
                        'url' => route('module.offline.detail', ['id' => $course->id]),
                        'signature' => $signature
                    ];
                    $user_id = [$profile->user_id];
                    $this->saveEmailCourseObject($params,$user_id,$course->id);
                }
            }
        }
    }

    public function saveEmailCourseObject(array $params,array $user_id,int $course_id)
    {
        $automail = new Automail();
        $automail->template_code = 'register_course_object';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $course_id;
        $automail->object_type = 'register_course_offline_object';
        $automail->addToAutomail();
    }
    public function sendMailChange(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, ['ids' => 'Khóa học']);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $course = OfflineCourse::find($id);
            $users = OfflineRegister::where('course_id', '=', $id)
                ->where('status', '=', 1)
                ->pluck('user_id')
                ->toArray();

            $automail = new Automail();
            $automail->template_code = 'course_change';
            $automail->params = [
                'code' => $course->code,
                'name' => $course->name,
                'start_date' => $course->start_date,
                'end_date' => $course->end_date,
                'url' => route('module.online.detail', ['id' => $id])
            ];
            $automail->users = $users;
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course->id;
            $automail->object_type = 'course_offline_change';
            $automail->addToAutomail();
        }

        json_message('Gửi mail thành công');
    }

    public function getDataHistory($course_id, Request $request) {
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
        $query->where('type', '=', 2);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->fullname = $row->lastname . ' ' . $row->firstname . ' (' . $row->code . ')';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function saveEmailDeletedCourse(array $params,array $user_id,int $register_id)
    {
        $automail = new Automail();
        $automail->template_code = 'delete_course';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $register_id;
        $automail->object_type = 'delete_course_offline';
        $automail->addToAutomail();
    }

    public function uploadfile(Request $request) {
        $this->validate($request, [
            'filenames' => "required|string"
        ]);

        $course_id = $request->course_id;
        if ($request->filenames) {
            $model = new OfflineCourseUpload();
            $model->upload = path_upload($request->filenames);
            $model->course_id = $course_id;
            $model->save();
        } else {
            return back()->with('false', 'Chưa chọn file');
        }
        return back()->with('success', 'Đã tải lên thư vện file');
    }
    //Thư viên file
    public function getDataLibraryFile($course_id,Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineCourseUpload::query();
        $query->select('*');
        $query->from('el_offline_course_upload as a');
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
    public function removeLibraryFile(Request $request) {
        $ids = $request->input('ids', null);
        OfflineCourseUpload::destroy($ids);
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
            $page_child[$item->id] = route('module.offline.get_tree_child', ['id' => $course_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }
    public function getTreeChild($course_id, Request $request){
        $parent_code = $request->parent_code;
        return view('offline::backend.offline.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    private function updateReportNewBC11($model){
        $course = OfflineCourse::query()->find($model->course_id);
        $training_form = TrainingType::query()->find($course->training_type_id);
        $training_location = TrainingLocation::query()->find($course->training_location_id);
        $subject = Subject::query()->find($course->subject_id);
        $course_time = $course->course_time;
        $total_register = OfflineRegister::whereCourseId($course->id)->count();

        $start = Carbon::parse($model->start_time);
        $end = Carbon::parse($model->end_time);
        $hours = $end->diffInHours($start);

        if ($model->end_time <= '12:00:00'){
            $time_schedule = 'Sáng '. get_date($model->lesson_date);
        }else{
            $time_schedule = 'Chiều '. get_date($model->lesson_date);
        }

        $cost_lecturer = $model->cost_teacher_main * $model->total_lessons;
        $cost_tuteurs = $model->cost_teach_type ? ($model->cost_teach_type * $model->total_lessons) : null;

        $training_teacher = TrainingTeacher::query()->whereIn('id', [$model->teacher_main_id, $model->teach_id])->get();
        foreach ($training_teacher as $item){
            $title = '';
            $unit_1 = '';
            $unit_2 = '';
            $unit_3 = '';
            if ($item->type == 1){
                $profile = Profile::query()->find($item->user_id);
                $title = @$profile->titles;
                $unit_1 = @$profile->unit;
                $unit_2 = @$unit_1->parent;
                $unit_3 = @$unit_2->parent;
            }

            ReportNewExportBC11::query()->create([
                'training_teacher_id' => $item->id,
                'schedule_id' => $model->id,
                'user_id' => $item->user_id,
                'user_code' => $item->code,
                'fullname' => $item->name,
                'account_number' => $item->account_number,
                'role_lecturer' => ($item->id == $model->teacher_main_id) ? 1 : 0,
                'role_tuteurs' => ($item->id == $model->teach_id) ? 1 : 0,
                'unit_id_1' => @$unit_1->id,
                'unit_code_1' => @$unit_1->code,
                'unit_name_1' => @$unit_1->name,
                'unit_id_2' => @$unit_2->id,
                'unit_code_2' => @$unit_2->code,
                'unit_name_2' => @$unit_2->name,
                'unit_id_3' => @$unit_3->id,
                'unit_code_3' => @$unit_3->code,
                'unit_name_3' => @$unit_3->name,
                'position_name' => null,
                'title_id' => @$title->id,
                'title_code' => @$title->code,
                'title_name' => @$title->name,
                'course_id' => @$course->id,
                'course_code' => @$course->code,
                'course_name' => @$course->name,
                'course_type' => 2,
                'subject_id' => @$subject->id,
                'subject_name' => @$subject->name,
                'training_form_id' => @$training_form->id,
                'training_form_name' => @$training_form->name,
                'course_time' => $course_time,
                'time_lecturer' => ($item->id == $model->teacher_main_id) ? $hours : null,
                'time_tuteurs' => ($item->id == $model->teach_id) ? $hours : null,
                'start_date' => @$course->start_date,
                'end_date' => @$course->end_date,
                'time_schedule' => $time_schedule,
                'training_location_id' => @$training_location->id,
                'training_location_name' => @$training_location->name,
                'total_register' => $total_register,
                'cost_lecturer' => ($item->id == $model->teacher_main_id) ? $cost_lecturer : null,
                'cost_tuteurs' => ($item->id == $model->teach_id) ? $cost_tuteurs : null,
            ]);
        }
    }

    // SAO CHÉP KHÁO HỌC
    public function copy(Request $request)
    {
        $ids = $request->input('ids', null);
        $user_role = UserRole::query()
            ->from('el_user_role as a')
            ->leftJoin('el_roles as b', 'b.id', '=', 'a.role_id')
            ->where('a.user_id', '=', Auth::id())
            ->first('b.code');

        foreach ($ids as $id) {
            $getCourse = OfflineCourse::findOrFail($id)->toArray();

            $courses = OfflineCourse::where('subject_id', '=', $getCourse['subject_id'])->get();
            $subject = Subject::find($getCourse['subject_id']);
            $count_course = count($courses);
            $check_count_course = '';
            for ($i = 1; $i <= $count_course; $i++) {
                $count = '00'.$i;
                $course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
                $check_subject_course_code = OfflineCourse::where('code',$course_code)->first();
                if(empty($check_subject_course_code)) {
                    $check_count_course = $count;
                    break;
                }
            }
            $saveCourse = new OfflineCourse();
            $saveCourse->fill($getCourse);
            if( !empty($check_count_course) ) {
                $saveCourse->code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$check_count_course;
            } else {
                $count_course = count($courses) + 1;
                $count = '00'.$count_course;
                $saveCourse->code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
            }
            $saveCourse->isopen = 0;
            $saveCourse->status = 2;
            $saveCourse->lock_course = 0;
            $saveCourse->approved_step = '';
            $saveCourse->save();
            CourseStatistic::update_course_insert_statistic($saveCourse->id,2);
        }

        json_message('Sao chép thành công');
    }

    // ĐÁNH GIÁ KHÓA HỌC
    public function saveRattingCourse($id, Request $request) {
        $model = RattingCourse::firstOrNew(['course_id' => $id, 'type' => 2]);
        $model->course_id = $id;
        $model->teacher = $request->teacher;
        $model->program_content = $request->program_content;
        $model->organization = $request->organization;
        $model->quality_course = $request->quality_course;
        $model->type = 2;
        $model->save();

        return response()->json([
            'message' => trans('backend.save_success'),
            'status' => 'success'
        ]);
    }
}
