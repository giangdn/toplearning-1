<?php

namespace Modules\CourseEducatePlan\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categories\District;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Province;
use App\Models\Categories\Subject;
use App\Models\Categories\TeacherType;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Certificate\Entities\Certificate;
use Modules\CourseEducatePlan\Entities\CourseEducatePlan;
use Modules\CourseEducatePlan\Entities\CourseEducatePlanCondition;
use Modules\CourseEducatePlan\Entities\CourseEducatePlanCost;
use Modules\CourseEducatePlan\Entities\CourseEducatePlanObject;
use Modules\CourseEducatePlan\Entities\CourseEducatePlanSchedule;
use Modules\CourseEducatePlan\Entities\CourseEducatePlanTeacher;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseUpload;
use Modules\PlanApp\Entities\PlanAppTemplate;
use Modules\Quiz\Entities\Quiz;
use Modules\Rating\Entities\RatingTemplate;
use Modules\TrainingPlan\Entities\TrainingPlan;
use Modules\VirtualClassroom\Entities\VirtualClassroom;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Facades\Module;

class CourseEducatePlanController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('courseeducateplan::backend.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
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

        $prefix= \DB::getTablePrefix();

        CourseEducatePlan::addGlobalScope(new DraftScope());
        $query = CourseEducatePlan::query(); 
        $query->select([
            'el_course_educate_plan.*',
            'c.name AS subject_name',
            'b.name AS training_program_name',
            \DB::raw("concat({$prefix}e.lastname,' ',{$prefix}e.firstname) as user_name")
        ]);
        $query->from('el_course_educate_plan');
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_course_educate_plan.training_program_id');
        $query->leftJoin('el_subject AS c', 'c.id', '=', 'el_course_educate_plan.subject_id');
        $query->leftJoin('el_level_subject as d', 'd.id', '=', 'el_course_educate_plan.level_subject_id');
        $query->leftJoin('el_profile as e', 'e.user_id', '=', 'el_course_educate_plan.created_by');

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_course_educate_plan.name', 'like', '%' . $search . '%');
                $subquery->orWhere('el_course_educate_plan.code', 'like', '%' . $search . '%');
            });
        }

        if ($training_program_id) {
            $query->where('b.id', '=', $training_program_id);
        }
        if ($level_subject_id){
            $query->where('d.id', '=', $level_subject_id);
        }
        if ($subject_id) {
            $query->where('c.id', '=', $subject_id);
        }

        if ($start_date) {
            $query->where('el_course_educate_plan.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('el_course_educate_plan.start_date', '<=', date_convert($end_date, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy('el_course_educate_plan.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.course_educate_plan.edit', ['id' => $row->id]);
            $row->register_deadline = get_date($row->register_deadline);
            $start_date = get_date($row->start_date);
            $end_date = get_date($row->end_date);
            $row->time = $start_date .' <i class="fa fa-arrow-right"></i> '.$end_date;

            $row->creat_course ='';
            if($row->status_convert==1)
                $row->creat_course ='<a href="'.route('module.offline.edit', ['id' => $row->course_convert_id]).'">Xem khóa học</a>';
            $row->actions ='';
            if($row->status_convert==1)
            $row->actions ='<a href="'.route('module.offline.attendance', ['id' => $row->course_convert_id]).'">Điểm danh</a> | <a href="'.route('module.offline.result', ['id' => $row->course_convert_id]).'">Nhập điểm</a>';
            //$row->approved_by =
                $row->approved_by ? Profile::fullname($row->approved_by) : '';
          //  $row->time_approved =
                $row->time_approved ? get_date($row->time_approved, 'h:i d/m/Y') : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $training_costs = TrainingCost::get();
        $course_cost =
        CourseEducatePlanCost::where('course_id', '=', $id)->get();
        $total_actual_amount = CourseEducatePlanCost::getTotalActualAmount($id);
        $total_plan_amount = CourseEducatePlanCost::getTotalPlanAmount($id);
        $teachers = CourseEducatePlanSchedule::getTeacher($id);
        $teacher_types = TeacherType::get();
        $templates = RatingTemplate::get();
        $plan_app_template = PlanAppTemplate::get();
        $training_plan = TrainingPlan::get();
        $certificate = Certificate::all();
        $qrcode_survey_after_course = null;
        $training_forms = TrainingForm::get();
        $province = Province::all();
        $quizs = Quiz::where('status','=',1)->get();

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
            $model = CourseEducatePlan::find($id);
            if (!$model) abort(404);
            $page_title = $model->name;
            $subject = Subject::where('id', $model->subject_id)->first();
            $training_program = TrainingProgram::where('id', $model->training_program_id)->first();
            $level_subject = LevelSubject::find($model->level_subject_id);

            $titles = Titles::where('status', '=', 1)->get();
            $unit = explode(',', $model->unit_id);

            $course_time = preg_replace("/[^0-9]/", '', $model->course_time);
            $course_time_unit = preg_replace("/[^a-z]/", '', $model->course_time);

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
            $condition = CourseEducatePlanCondition::where('course_id', '=', $id)->first();

            return view('courseeducateplan::backend.form', [
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
                'teacher_types' => $teacher_types,
                'templates' => $templates,
                'plan_app_template' => $plan_app_template,
                'training_plan' => $training_plan,
                'unit' => $unit,
                'course_time' => $course_time,
                'course_time_unit' => $course_time_unit,
                'certificate' => $certificate,
                'qrcode_survey_after_course' => $qrcode_survey_after_course,
                'units' => $units,
                'unit_manager_lv2' => $unit_manager_lv2,
                'level_subject' => $level_subject,
                'corporations' => $corporations,
                'training_forms' => $training_forms,
                'province' => $province,
                'district' => $district,
                'training_location' => $training_location,
                'quizs' => $quizs,
                'condition' => $condition,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }

        $model = new CourseEducatePlanCost();
        $page_title = trans('backend.add_new') ;
        $training_location = TrainingLocation::all();

        return view('courseeducateplan::backend.form', [
            'model' => $model,
            'page_title' => $page_title,
            'templates' => $templates,
            'plan_app_template' => $plan_app_template,
            'training_plan' => $training_plan,
            'course_time' => null,
            'course_time_unit' => null,
            'certificate' => $certificate,
            'qrcode_survey_after_course' => $qrcode_survey_after_course,
            'units' => $units,
            'unit_manager_lv2' => $unit_manager_lv2,
            'corporations' => $corporations,
            'training_forms' => $training_forms,
            'province' => $province,
            'district' => null,
            'training_location' => $training_location,
            'quizs' => $quizs,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'name' => 'required',
            'in_plan' => 'nullable|exists:el_training_plan,id',
            'course_time' => 'nullable',
            'image' => 'nullable|string',
            'document' => 'nullable|string',
            'num_lesson' => 'nullable',
            'action_plan' => 'required|in:0,1',
            'start_date' => 'required|date_format:d/m/Y',
            'plan_app_template' => 'required_if:action_plan,1|nullable|integer',
            'plan_app_day' => 'required_if:action_plan,1|nullable|integer|max:1000',
        ], $request);

        $course_time_unit = $request->post('course_time_unit');
        $unit_id = $request->post('unit_id');

        $subject = Subject::find($request->subject_id);

        $model = CourseEducatePlan::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        $model->has_cert = $request->post('has_cert', 0);
        $model->start_date = date_convert($request->input('start_date'));
        $model->end_date = $request->input('end_date') ? date_convert($request->input('end_date'), '23:59:59') : null;
        $model->register_deadline = $request->input('register_deadline') ? date_convert($request->input('register_deadline'), '23:59:59') : null;

        if ($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image);
        }
        
        $model->document = path_upload($model->document);
        $model->unit_id = is_array($unit_id) ? implode(',', $unit_id) : null;
        $model->course_time = $request->input('course_time') . ' ' . $course_time_unit;
        $model->level_subject_id = $subject->level_subject_id;

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

        if (empty($model->id)) {
            $model->created_by = Auth::id();
        }

        $model->updated_by = Auth::id();
        $model->status = 2;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.course_educate_plan.edit', ['id' => $model->id])
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);

        CourseEducatePlan::destroy($ids);

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

    public function ajaxIsopenPublish(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('lacourse.course'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach ($ids as $id) {
            $model = CourseEducatePlanCost::findOrFail($id);
            $model->isopen = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function ajaxGetCourseCode(Request $request)
    {
        $this->validateRequest([
            'subject_id' => 'required',
        ], $request, [
            'subject_id' => 'Mã học phần',
        ]);

        $subject_id = $request->input('subject_id');
        $subject = Subject::find($subject_id);
        $courses = CourseEducatePlan::where('subject_id', '=', $subject->id)->get();

        return response()->json([
            'id' => count($courses),
            'subject_code' => $subject->code,
            'description' => $subject->description,
            'content' => $subject->content,
        ]);
    }

    public function approve(Request $request)
    {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            $query = CourseEducatePlan::query();
            $query->where('id', $id);
            $query->update([
                'status' => $status,
                'approved_by' => Auth::id(),
                'time_approved' => date('Y-m-d h:i:s'),
            ]);
        }
        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }

    public function saveCost($course_id, Request $request)
    {
        $find = [',', ';', '.'];
        $cost_ids = $request->post('id');
        $plan_amounts = str_replace($find, '', $request->post('plan_amount'));
        $actual_amounts = str_replace($find, '', $request->post('actual_amount'));
        $notes = $request->post('note');

        foreach ($cost_ids as $key => $cost_id) {

            if (CourseEducatePlanCost::checkCostExists($course_id, $cost_id)) {
                CourseEducatePlanCost::where('course_id', '=', $course_id)
                    ->where('cost_id', '=', $cost_id)
                    ->update([
                        'plan_amount' => (float)$plan_amounts[$key] ? $plan_amounts[$key] : 0,
                        'actual_amount' => (float)$actual_amounts[$key] ? $actual_amounts[$key] : 0,
                        'notes' => $notes[$key] ? $notes[$key] : '',
                    ]);
                continue;
            }
            if($plan_amounts[$key] > 0 || $actual_amounts[$key] > 0) {
                $model = new CourseEducatePlanCost();
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

    public function saveObject($course_id, Request $request)
    {
        $this->validateRequest([
            'type' => 'required|in:1,2',
        ], $request, [
            'title' => 'Chức danh',
            'unit' => 'Đơn vị',
            'type' => 'Loại đối tượng',
        ]);

        $titles = $request->post('title');
        $units = $request->post('unit');
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

                    if (CourseEducatePlanObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->whereNull('title_id')->exists()) {
                        continue;
                    }

                    if (CourseEducatePlanObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->where('title_id', '=', $item)->exists()) {
                        continue;
                    }

                    if (CourseEducatePlanObject::where('course_id', '=', $course_id)->whereNull('unit_id')->where('title_id', '=', $item)->exists()) {
                        CourseEducatePlanObject::where('course_id', '=', $course_id)
                            ->whereNull('unit_id')
                            ->where('title_id', '=', $item)
                            ->update([
                                'unit_id' => $unit->id,
                                'unit_level' => $unit->level,
                            ]);
                    }else{
                        $model = new CourseEducatePlanObject();
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

                if (CourseEducatePlanObject::where('course_id', '=', $course_id)
                    ->where('unit_id', '=', $item)
                    ->exists()) {
                    continue;
                }

                if (!Unit::where('id', '=', $item)->exists()) {
                    continue;
                }

                $unit = Unit::find($item);

                $model = new CourseEducatePlanObject();
                $model->unit_id = $item;
                $model->unit_level = $unit->level;
                $model->type = $type;
                $model->course_id = $course_id;
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
                $model->save();
            }
        }

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

        $query = CourseEducatePlanObject::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
            'c.name AS unit_name'
        ]);

        $query->from('el_course_educate_plan_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->where('a.course_id', '=', $course_id);
        $count = $query->count();
        $query->orderBy('a.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

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
        CourseEducatePlanObject::destroy($item);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveCondition($course_id, Request $request){
        $this->validateRequest([
            'ratio' => 'nullable|numeric|min:1|max:100',
            'minscore' => 'nullable|numeric|min:1',
        ], $request, CourseEducatePlanCondition::getAttributeName());

        if(CourseEducatePlanCondition::checkExists($course_id)){
            CourseEducatePlanCondition::where('course_id', '=', $course_id)
                ->update([
                    'ratio' => $request->input('ratio'),
                    'minscore' => $request->input('minscore'),
                    'survey' => $request->survey,
                    'certificate' => $request->certificate,
                ]);
        }else{

            $model = new CourseEducatePlanCondition();
            $model->ratio = $request->input('ratio');
            $model->minscore = $request->input('minscore');
            $model->survey = $request->survey;
            $model->certificate = $request->certificate;
            $model->course_id = $course_id;
            $model->save();
        }
        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function saveSchedule($course_id, Request $request){
        $this->validateRequest([
            'start_time' => 'required',
            'end_time' => 'required',
            'lesson_date' => 'required|date_format:d/m/Y',
            'teacher_main_id' => 'required|exists:el_training_teacher,id',
            'teach_id' => 'nullable|exists:el_training_teacher,id',
            'cost_teacher_main' => 'nullable|min:0',
            'cost_teach_type' => 'nullable|min:0',
            'total_lessons' => 'required|numeric',

        ], $request, CourseEducatePlanSchedule::getAttributeName());

        $teacher_main_id = $request->input('teacher_main_id');
        $lesson_date = date_convert($request->input('lesson_date'));
        $start_time = $request->input('start_time') . ':00';
        $end_time = $request->input('end_time') . ':00';

        $check = CourseEducatePlan::where('id', '=', $course_id)
            ->where(function ($sub) use ($lesson_date){
                $sub->orWhere('start_date', '>', $lesson_date);
                $sub->orWhere('end_date', '<', $lesson_date);
            })->exists();
        if ($check){
            json_message('Lịch học không nằm trong thời gian học', 'error');
        }

        $check_exist1 = CourseEducatePlanSchedule::where('lesson_date', '=', $lesson_date)
            ->where('start_time', '<=', $start_time)
            ->where('end_time', '>=', $start_time)
            ->where('course_id', '=', $course_id)
            ->where('teacher_main_id', '=', $teacher_main_id)
            ->exists();

        if ($check_exist1){
            json_message('Giờ học đã tồn tại', 'error');
        }

        $check_exist2 = CourseEducatePlanSchedule::where('lesson_date', '=', $lesson_date)
            ->where('start_time', '<=', $end_time)
            ->where('end_time', '>=', $end_time)
            ->where('course_id', '=', $course_id)
            ->where('teacher_main_id', '=', $teacher_main_id)
            ->exists();

        if ($check_exist2){
            json_message('Giờ học đã tồn tại', 'error');
        }

        if(get_date($start_time, 'H') >= get_date($end_time, 'H') && get_date($start_time, 'i') >= get_date($end_time, 'i')){
            json_message('Giờ kết thúc phải sau Giờ bắt đầu', 'error');
        }

        if ($request->input('teacher_main_id') == $request->input('teach_id')){
            json_message('Giảng viên chính và phụ không được trùng', 'error');
        }

        $model = new CourseEducatePlanSchedule();
        $model->start_time = $start_time;
        $model->end_time = $end_time;
        $model->lesson_date = $lesson_date;
        $model->teacher_main_id = $request->input('teacher_main_id');
        $model->teach_id = $request->input('teach_id');
        $model->cost_teacher_main = $request->input('cost_teacher_main');
        $model->cost_teach_type = $request->input('cost_teach_type');
        $model->total_lessons = $request->input('total_lessons');
        $model->course_id = $course_id;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Thêm thành công',
            ]);
        }
    }

    public function getSchedule($course_id, Request $request){
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseEducatePlanSchedule::query();
        $query->select(['a.*', 'b.name as main_name', 'c.name as teach_name']);
        $query->from('el_course_educate_plan_schedule AS a');
        $query->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_main_id');
        $query->leftJoin('el_training_teacher AS c', 'c.id', '=', 'a.teach_id');
        $query->where('a.course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.lesson_date', 'ASC');
        $query->orderBy('a.start_time', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->start_time = get_date($row->start_time, 'H:i') . ' ' . get_date($row->lesson_date, 'd/m/Y');
            $row->end_time = get_date($row->end_time, 'H:i') . ' ' . get_date($row->lesson_date, 'd/m/Y');
            $row->cost_teacher_main = number_format($row->total_lessons * $row->cost_teacher_main, 0). ' VNĐ';
            $row->cost_teach_type = number_format($row->total_lessons * $row->cost_teach_type, 0) . ' VNĐ';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeSchedule($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request);

        $item = $request->input('ids');
        CourseEducatePlanSchedule::destroy($item);

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
            $page_child[$item->id] = route('module.course_educate_plan.get_tree_child', ['id' => $course_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }

    public function getTreeChild($course_id, Request $request){
        $parent_code = $request->parent_code;
        return view('courseeducateplan::backend.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    public function convert(Request $request){
        $course_id = $request->course_id;

        $course_educate_plan = CourseEducatePlan::query()->find($course_id);
        $subject = Subject::find($course_educate_plan->subject_id);

            $offline_course = OfflineCourse::where('subject_id', '=', $subject->id)->count();
            $offline_code = $subject->code.'_'.date('m_Y').'_'.($offline_course + 1);
            $course_educate_plan->update([
                'code' => $offline_code,
                'status_convert' => 1
            ]);

            $sql = CourseEducatePlan::selectRaw('code, name, unit_id, 1, training_form_id, plan_detail_id, description, 0, 2, start_date, end_date, register_deadline, image, 0, document, '. Auth::id() .', '. Auth::id() .', training_program_id, level_subject_id, subject_id, training_location_id, training_unit, training_area_id, training_partner_id, content, views, category_id, course_time, num_lesson, action_plan, plan_app_template, plan_app_day, cert_code, has_cert, teacher_id, rating, template_id, commit, commit_date, coefficient, cost_class, quiz_id, unit_by, now(), now()')->where('id', '=', $course_id);

            DB::table('el_offline_course')->insertUsing(['code', 'name', 'unit_id', 'in_plan', 'training_form_id', 'plan_detail_id', 'description', 'isopen', 'status', 'start_date', 'end_date', 'register_deadline', 'image', 'max_student', 'document', 'created_by', 'updated_by', 'training_program_id', 'level_subject_id', 'subject_id','training_location_id', 'training_unit', 'training_area_id', 'training_partner_id', 'content', 'views', 'category_id','course_time', 'num_lesson', 'action_plan', 'plan_app_template','plan_app_day','cert_code','has_cert', 'teacher_id', 'rating', 'template_id', 'commit', 'commit_date', 'coefficient', 'cost_class', 'quiz_id', 'unit_by', 'created_at', 'updated_at'], $sql);

            $offline = OfflineCourse::query()->orderByDesc('id')->first();

            $sql_course_object = CourseEducatePlanObject::selectRaw($offline->id .', title_id, unit_id, unit_level, type, '. Auth::id() .', '. Auth::id() .', now(), now()')->where('course_id', '=', $course_id);
            if (count($sql_course_object->get()) > 0){
                DB::table('el_offline_object')->insertUsing(['course_id','title_id','unit_id','unit_level','type','created_by','updated_by','created_at', 'updated_at'], $sql_course_object);
            }

            $sql_course_teacher = CourseEducatePlanTeacher::selectRaw($offline->id .', teacher_id, now(), now()')->where('course_id', '=', $course_id);
            if (count($sql_course_teacher->get()) > 0){
                DB::table('el_offline_course_teachers')->insertUsing(['course_id', 'teacher_id','created_at', 'updated_at'], $sql_course_teacher);
            }

            $sql_course_schedule = CourseEducatePlanSchedule::selectRaw($offline->id .', start_time, end_time, lesson_date, teacher_main_id, teach_id, cost_teacher_main, cost_teach_type, total_lessons, now(), now()')->where('course_id', '=', $course_id);

            if (count($sql_course_schedule->get()) > 0){
                DB::table('el_offline_schedule')->insertUsing(['course_id', 'start_time', 'end_time', 'lesson_date', 'teacher_main_id', 'teach_id', 'cost_teacher_main', 'cost_teach_type', 'total_lessons','created_at', 'updated_at'], $sql_course_schedule);
            }

            $sql_course_cost = CourseEducatePlanCost::selectRaw($offline->id .', cost_id, plan_amount, actual_amount, notes, now(), now()')->where('course_id', '=', $course_id);
            if (count($sql_course_cost->get()) > 0){
                DB::table('el_offline_course_cost')->insertUsing(['course_id','cost_id','plan_amount','actual_amount','notes','created_at', 'updated_at'], $sql_course_cost);
            }

            $sql_course_condition = CourseEducatePlanCondition::selectRaw($offline->id .', ratio, minscore, survey, certificate, now(), now()')->where('course_id', '=', $course_id);
            if (count($sql_course_condition->get()) > 0){
                DB::table('el_offline_condition')->insertUsing(['course_id','ratio','minscore','survey','certificate','created_at', 'updated_at'], $sql_course_condition);
            }
        $cep = CourseEducatePlan::find($course_id);
        $cep->course_convert_id = $offline->id;
            $cep->save();

        json_message('Chuyển đổi thành công');
    }

    public function teacher($course_id) {
        // $course = CourseEducatePlanCost::find($course_id);
        $course = CourseEducatePlan::find($course_id);
        $page_title = $course->name;
        $teachers = TrainingTeacher::get();

        return view('courseeducateplan::backend.teacher', [
            'page_title' => $page_title,
            'course' => $course,
            'teachers' => $teachers,
        ]);
    }

    public function getDataTeacher($course_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseEducatePlanTeacher::query();
        $query->select(['a.*', 'b.name as teacher_name', 'b.email as teacher_email', 'b.phone as teacher_phone']);
        $query->from('el_course_educate_plan_teacher AS a');
        $query->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id');
        $query->where('a.course_id', '=', $course_id);

        if ($search) {
            $query->where('b.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('b.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveTeacher($course_id, Request $request) {
        $this->validateRequest([
            'teacher_id' => 'required|exists:el_training_teacher,id',
        ], $request, CourseEducatePlanTeacher::getAttributeName());

        $teacher_id = $request->input('teacher_id');

        if(CourseEducatePlanTeacher::checkExists( $course_id, $teacher_id)){
            json_message('Giảng viên đã tồn tại', 'error');
        }
        $model = new CourseEducatePlanTeacher();
        $model->teacher_id = $teacher_id;
        $model->course_id = $course_id;

        if ($model->save()) {
            $redirect = route('module.course_educate_plan.teacher', ['id' => $course_id]);
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => $redirect,
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function removeTeacher($course_id, Request $request) {
        $ids = $request->input('ids', null);
        CourseEducatePlanTeacher::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
