<?php

namespace Modules\QuizEducatePlan\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\QuizEducatePlan\Entities\QuizEducatePlanSuggest;
use Modules\Quiz\Entities\QuestionCategory;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Profile;
use App\Permission;
use App\PermissionUser;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\QuizEducatePlan\Entities\QuizEducatePlanPart;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\QuizEducatePlan\Entities\QuizEducatePlanRank;
use Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion;
use Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting;
use Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher;
use Modules\Quiz\Entities\QuizTemplate;
use Modules\Quiz\Entities\QuizTemplateQuestionRand;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesQuestion;
use Modules\Quiz\Entities\QuizTemplatesQuestionCategory;
use Modules\Quiz\Entities\QuizTemplatesRank;
use Modules\Quiz\Entities\QuizTemplatesSetting;
use Modules\Quiz\Entities\QuizType;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Facades\Module;
use Modules\QuizEducatePlan\Entities\QuizEducatePlan;

class QuizEducatePlanController extends Controller
{
    public function index($idsg)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('quizeducateplan::backend.index',[
            "idsg"=>$idsg,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }
    public function indexSuggest()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('quizeducateplan::backend.index_suggest',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getDataSuggest(Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);
        $query = QuizEducatePlanSuggest::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query ->count();
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();

        foreach ($rows as $row) {
            $row->name ='<a href="javascript:void(0)" data-id="'.$row->name.'"">'.$row->name.'</a>';
            $row->quizs ='<a href="'. route('module.quiz_educate_plan.index',["idsg"=>$row->id]).'" ><i class="fa fa-bars" aria-hidden="true"></i></a>';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveSuggest(Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, QuizEducatePlanSuggest::getAttributeName());

        $model = QuizEducatePlanSuggest::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->created_by = Auth::id();
        $model->updated_by = Auth::id();
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.quiz_educate_plan_suggest')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function getData($idsg, Request $request)
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

        $query = QuizEducatePlan::query();
        $query->select([
            'a.*',
            'b.name AS type_name'
        ]);
        $query->from('el_quiz_educate_plan as a');
        $query->leftJoin('el_quiz_type AS b', 'b.id', '=', 'a.type_id');

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('a.name', 'like', '%' . $search . '%');
                $subquery->orWhere('a.code', 'like', '%' . $search . '%');
            });
        }

        if ($start_date) {
            $query->where('a.start_date', '>=', date_convert($start_date));
        }
        if ($end_date) {
            $query->where('a.start_date', '<=', date_convert($end_date, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.quiz_educate_plan.edit', ['idsg' =>$idsg,'id' => $row->id]);
            $start_date = get_date($row->start_date);
            $end_date = get_date($row->end_date);
            $row->time = $start_date .' <i class="fa fa-arrow-right"></i> '.$end_date;

            $row->creat_course ='';
            if($row->status_convert==1)
                $row->creat_course ='<a href="'.route('module.quiz.edit', ['id' => $row->quiz_convert_id]).'">Xem kỳ thi</a>';
            $row->actions ='';
            if($row->status_convert==1)
            $row->actions ='<a href="'.route('module.quiz.register', ['id' => $row->quiz_convert_id]).'">Thí sinh nội bộ</a> | <a href="'.route('module.quiz.user_second_note', ['id' => $row->course_convert_id]).'">Thí sinh bên ngoài</a>';
            //$row->approved_by =
                $row->approved_by ? Profile::fullname($row->approved_by) : '';
          //  $row->time_approved =
                $row->time_approved ? get_date($row->time_approved, 'h:i d/m/Y') : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($idsg, $id = null) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $user = Profile::find(Auth::id());

        $model = QuizEducatePlan::firstOrNew(['id' => $id]);
        $quiz_type = QuizType::find($model->type_id, ['id', 'name']);
        $page_title = $model->name ? $model->name : trans('backend.add_new') ;
        $teachers = QuizEducatePlanTeacher::getTeacherByQuiz($id);

        $unit = Unit::firstOrNew(['id' => $model->unit_id]);
        $setting = QuizEducatePlanSetting::where('quiz_id', '=', $id)->first();
        $qrcode_quiz = json_encode(['quiz'=>$id,'course_type'=>1,'survey'=>$model->template_id,'type'=>'survey_after_course']);

        $quiz_template = QuizTemplates::where('status', '=', 1)->where('is_open', '=', 1)->get();

        return view('quizeducateplan::backend.form', [
            'model' => $model,
            'page_title' => $page_title,
            'teachers' => $teachers,
            'unit' => $unit,
            'unit_user' => $user->unit,
            'setting' => $setting,
            'quiz_type' => $quiz_type,
            'qrcode_quiz' => $qrcode_quiz,
            'quiz_template' => $quiz_template,
            'idsg' => $idsg,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save($idsg, Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_quiz,code,'. $request->id,
            'name' => 'required',
            'limit_time' => 'required|min:1',
            'pass_score' => 'required|min:0|max:100',
            'max_score' => 'required|min:0|max:100',
            'max_attempts' => 'required',
            'questions_perpage' => 'required',
            'grade_methor' => 'required',
            'img' => 'nullable|string',
        ], $request, Quiz::getAttributeName());

        $questions_perpage = $request->input('questions_perpage');
        $limit_time = $request->input('limit_time');
        $pass_score = $request->input('pass_score');
        $max_score = $request->input('max_score');
        $shuffle = $request->post('shuffle_answers');

        $quiz_template_id = $request->quiz_template_id;

        $model = QuizEducatePlan::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());

        if (isset($request->id) && isset($quiz_template_id) && $quiz_template_id != $model->quiz_template_id){
            QuizEducatePlanPart::query()->where('quiz_id', '=', $model->id)->delete();
            QuizEducatePlanSetting::query()->where('quiz_id', '=', $model->id)->delete();
            QuizEducatePlanQuestion::query()->where('quiz_id', '=', $model->id)->delete();
            QuizEducatePlanQuestionCategory::query()->where('quiz_id', '=', $model->id)->delete();
        }

        $model->shuffle_answers = if_empty($shuffle, 0);
        
        if ($request->img) {
            $sizes = config('image.sizes.medium');
            $model->img = upload_image($sizes, $request->img);
        }
        
        if($limit_time < 1){
            json_message('Thời gian làm bài phải lớn hơn 1 phút', 'error');
        }

        if($pass_score < 0 || $pass_score > 100){
            json_message('Điểm chuẩn trong khoảng 0 đến 100', 'error');
        }

        if($max_score < 0 || $max_score > 100){
            json_message('Điểm tối đa trong khoảng 0 đến 100', 'error');
        }

        if ($pass_score > $max_score){
            json_message('Điểm chuẩn không được lớn hơn điểm tối đa', 'error');
        }

        if($questions_perpage < 0){
            json_message('Số câu hỏi ít nhất là 0', 'error');
        }

        $model->created_by = Auth::id();
        $model->updated_by = Auth::id();
        $model->status = 2;

        if ($model->save()) {
            if ($quiz_template_id){
                $rank = QuizEducatePlanRank::query()->where('quiz_id', '=', $model->id);
                if (!$rank->exists()){
                    $quiz_template_rank = QuizTemplatesRank::selectRaw($model->id . ', rank, score_min, score_max, now(), now()')->where('quiz_id', '=', $quiz_template_id);
                    DB::table('el_quiz_educate_plan_rank')->insertUsing(['quiz_id', 'rank', 'score_min', 'score_max', 'created_at', 'updated_at'], $quiz_template_rank);
                }

                $setting = QuizEducatePlanSetting::query()->where('quiz_id', '=', $model->id);
                if (!$setting->exists()){
                    $quiz_template_setting = QuizTemplatesSetting::selectRaw($model->id . ', after_test_review_test, after_test_yes_no, after_test_score, after_test_specific_feedback, after_test_general_feedback, after_test_correct_answer, exam_closed_review_test, exam_closed_yes_no, exam_closed_score, exam_closed_specific_feedback, exam_closed_general_feedback, exam_closed_correct_answer, now(), now()')->where('quiz_id', '=', $quiz_template_id);
                    DB::table('el_quiz_educate_plan_setting')->insertUsing(['quiz_id', 'after_test_review_test', 'after_test_yes_no', 'after_test_score', 'after_test_specific_feedback', 'after_test_general_feedback', 'after_test_correct_answer', 'exam_closed_review_test', 'exam_closed_yes_no', 'exam_closed_score', 'exam_closed_specific_feedback', 'exam_closed_general_feedback', 'exam_closed_correct_answer', 'created_at', 'updated_at'], $quiz_template_setting);
                }

                $quiz_question = QuizEducatePlanQuestion::query()->where('quiz_id', '=', $model->id);
                if (!$quiz_question->exists()){
                    $quiz_template_question_category = QuizTemplatesQuestionCategory::where('quiz_id', '=', $quiz_template_id)->get();
                    if (count($quiz_template_question_category) > 0){
                        foreach ($quiz_template_question_category as $item){
                            $quiz_question_category = new QuizEducatePlanQuestionCategory();
                            $quiz_question_category->quiz_id = $model->id;
                            $quiz_question_category->name = $item->name;
                            $quiz_question_category->num_order = $item->num_order;
                            $quiz_question_category->percent_group = $item->percent_group;
                            $quiz_question_category->save();

                            $quiz_template_question = QuizTemplatesQuestion::selectRaw($model->id . ', question_id, qcategory_id, random, num_order, '. $quiz_question_category->id .', max_score, now(), now()')->where('qqcategory', '=', $item->id)->where('quiz_id', '=', $quiz_template_id);
                            DB::table('el_quiz_educate_plan_question')->insertUsing(['quiz_id', 'question_id', 'qcategory_id', 'random', 'num_order', 'qqcategory', 'max_score', 'created_at', 'updated_at'], $quiz_template_question);
                        }
                    }else{
                        $quiz_template_question = QuizTemplatesQuestion::selectRaw($model->id . ', question_id, qcategory_id, random, num_order, qqcategory, max_score, now(), now()')->where('quiz_id', '=', $quiz_template_id);
                        DB::table('el_quiz_educate_plan_question')->insertUsing(['quiz_id', 'question_id', 'qcategory_id', 'random', 'num_order', 'qqcategory', 'max_score', 'created_at', 'updated_at'], $quiz_template_question);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.quiz_educate_plan.index',["idsg"=>$idsg])
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => trans('lageneral.save_error')
        ]);
    }
    public function getDataPart($quiz_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizEducatePlanPart::query();
        $query->where('quiz_id', '=', $quiz_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date, 'H:i d/m/Y');
            $row->end_date = get_date($row->end_date, 'H:i d/m/Y');
            $qrcode_quiz = json_encode(['quiz'=>$quiz_id,'part'=>$row->id,'type'=>'quiz']);
            $row->qrcode = \QrCode::size(300)->generate($qrcode_quiz);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function savePart($id, Request $request){
        $this->validateRequest([
            'name_part' => 'required',
            'start_date' => 'required',
            'start_hour' => 'required',
            'start_min' => 'required',
        ], $request);

        $quiz = QuizEducatePlan::find($id);

        if ($quiz->quiz_type != 1){
            if (is_null($request->input('end_date'))){
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian kết thúc không được trống',
                ]);
            }
        }

        $name_part = $request->input('name_part');
        $start_time = $request->input('start_hour') . ':' . $request->input('start_min') . ':00';
        $end_time = $request->input('end_hour') . ':' . $request->input('end_min') . ':00';

        $start_date = date_convert($request->input('start_date'), $start_time);
        $end_date = '';
        if ($request->input('end_date')){
            $end_date = date_convert($request->input('end_date'), $end_time);
        }

        $check1 = QuizEducatePlanPart::query();
        $check1->where('start_date', '<=', $start_date);
        $check1->where('end_date', '>=', $start_date);
        $check1->where('quiz_id', '=', $id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian ca thi không họp lệ',
            ]);
        }

        if ($request->input('end_date')){
            $check2 = QuizEducatePlanPart::query();
            $check2->where('start_date', '<=', $end_date);
            $check2->where('end_date', '>=', $end_date);
            $check2->where('quiz_id', '=', $id);
            if ($check2->exists()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian ca thi không họp lệ',
                ]);
            }
        }

        $model = new QuizEducatePlanPart();
        $model->quiz_id = $id;
        $model->name = $name_part;
        $model->start_date = $start_date;
        $model->end_date = $request->input('end_date') ? $end_date : null;

        if ($request->input('end_date')){
            if($model->start_date >= $model->end_date){
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian ca thi không họp lệ',
                ]);
            }
        }

        if($model->save()){
            json_message('ok');
        }

    }
    public function removePart($id, Request $request) {
        $ids = $request->input('ids', null);
        QuizEducatePlanPart::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }


    public function getDataRank($id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizEducatePlanRank::where('quiz_id', '=', $id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->score_min = round($row->score_min,1);
            $row->score_max = round($row->score_max,1);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveRank($id, Request $request){
        $this->validateRequest([
            'rank' => 'required',
            'score_min' => 'required',
            'score_max' => 'required',
        ], $request);

        $rank = $request->input('rank');
        $score_min = $request->input('score_min');
        $score_max = $request->input('score_max');

        $quiz = QuizEducatePlan::find($id);
        if($score_min < 0 || $score_max > $quiz->max_score || $score_min > $score_max || $score_min > $quiz->max_score){
            json_result([
                'status' => 'error',
                'message' => 'Điểm nhập không họp lệ',
            ]);
        }

        $check1 = QuizEducatePlanRank::query();
        $check1->where('score_min', '<=', $score_min);
        $check1->where('score_max', '>=', $score_min);
        $check1->where('quiz_id', '=', $id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm nhập không họp lệ',
            ]);
        }

        $check2 = QuizEducatePlanRank::query();
        $check2->where('score_min', '<=', $score_max);
        $check2->where('score_max', '>=', $score_max);
        $check2->where('quiz_id', '=', $id);
        if ($check2->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm nhập không họp lệ',
            ]);
        }
        $model = new QuizEducatePlanRank();
        $model->quiz_id = $id;
        $model->rank = $rank;
        $model->score_min = $score_min;
        $model->score_max = $score_max;

        if($model->save()){
            json_message('ok');
        }
    }

    public function removeRank($id, Request $request) {
        $ids = $request->input('ids', null);
        QuizEducatePlanRank::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveTeacher($id, Request $request) {
        QuizEducatePlanTeacher::where('quiz_id', '=', $id)->delete();

        $teachers = (array) $request->input('teachers', []);

        foreach ($teachers as $teacher) {
            $model = new QuizEducatePlanTeacher();
            $model->quiz_id = $id;
            $model->teacher_id = $teacher;
            $model->save();
        }

        json_message(trans('lageneral.successful_save'));
    }

    public function saveSetting($id, Request $request){

        $model = QuizEducatePlanSetting::firstOrNew(['id' => $request->id]);
        $model->after_test_review_test = $request->after_test_review_test;
        $model->after_test_yes_no = $request->after_test_yes_no;
        $model->after_test_score = $request->after_test_score;
        $model->after_test_specific_feedback = $request->after_test_specific_feedback;
        $model->after_test_general_feedback = $request->after_test_general_feedback;
        $model->after_test_correct_answer = $request->after_test_correct_answer;
        $model->exam_closed_review_test = $request->exam_closed_review_test;
        $model->exam_closed_yes_no = $request->exam_closed_yes_no;
        $model->exam_closed_score = $request->exam_closed_score;
        $model->exam_closed_specific_feedback = $request->exam_closed_specific_feedback;
        $model->exam_closed_general_feedback = $request->exam_closed_general_feedback;
        $model->exam_closed_correct_answer = $request->exam_closed_correct_answer;
        $model->quiz_id = $id;

        $model->save();

        json_message(trans('lageneral.successful_save'));
    }


    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);

        QuizEducatePlan::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }


    public function removeSuggest(Request $request)
    {
        $ids = $request->input('ids', null);

        QuizEducatePlanSuggest::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }



    public function ajaxIsopenPublish(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Khóa học',
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
            'message' => trans('lageneral.successful_save')
        ]);
    }

    public function approve(Request $request)
    {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            $query = QuizEducatePlan::query();
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


    public function convert(Request $request){
        $quiz_id = $request->quiz_id;
        $quiz_educate_plan = QuizEducatePlan::query()->find($quiz_id);

        $sql = QuizEducatePlan::selectRaw('code, name, unit_id, type_id, limit_time, view_result, shuffle_answers, shuffle_question,  paper_exam, questions_perpage, pass_score, max_score, description, max_attempts, grade_methor, status, course_id, course_type, quiz_type, webcam_require, question_require, times_shooting_webcam, '. Auth::id() .',  '. Auth::id() .', unit_by, quiz_template_id, now(), now()')->where('id', '=', $quiz_id);

        DB::table('el_quiz')->insertUsing(['code', 'name', 'unit_id', 'type_id', 'limit_time', 'view_result', 'shuffle_answers', 'shuffle_question', 'paper_exam', 'questions_perpage', 'pass_score', 'max_score', 'description', 'max_attempts', 'grade_methor', 'status', 'course_id', 'course_type', 'quiz_type', 'webcam_require', 'question_require', 'times_shooting_webcam', 'created_by',  'updated_by', 'unit_by', 'quiz_template_id', 'created_at', 'updated_at'], $sql);

            $quiz = Quiz::query()->orderByDesc('id')->first();

            $sql_quiz_part = QuizEducatePlanPart::selectRaw($quiz_id .', name, start_date, end_date, now(), now()')->where('quiz_id', '=', $quiz_id);
            if (count($sql_quiz_part->get()) > 0){
                DB::table('el_quiz_educate_plan_part')->insertUsing(['quiz_id', 'name', 'start_date', 'end_date', 'created_at','updated_at'], $sql_quiz_part);
            }

            $sql_quiz_question = QuizEducatePlanQuestion::selectRaw($quiz_id.', question_id, qcategory_id, random, num_order, max_score, qqcategory, now(), now()')->where('quiz_id', '=', $quiz_id);
            if (count($sql_quiz_question->get()) > 0){
                DB::table('el_quiz_educate_plan_question')->insertUsing(['quiz_id', 'question_id', 'qcategory_id', 'random', 'num_order', 'max_score', 'qqcategory','created_at', 'updated_at'], $sql_quiz_question);
            }

            $sql_quiz_rank = QuizEducatePlanRank::selectRaw($quiz_id .', rank, score_min, score_max, now(), now()')->where('quiz_id', '=', $quiz_id);

            if (count($sql_quiz_rank->get()) > 0){
                DB::table('el_quiz_educate_plan_rank')->insertUsing(['quiz_id', 'rank', 'score_min', 'score_max', 'created_at', 'updated_at'], $sql_quiz_rank);
            }

            $sql_quiz_setting= QuizEducatePlanSetting::selectRaw($quiz_id .', quiz_id, after_test_review_test, after_test_yes_no, after_test_score, after_test_specific_feedback, after_test_general_feedback, after_test_correct_answer, exam_closed_review_test, exam_closed_yes_no, exam_closed_score, exam_closed_specific_feedback, exam_closed_general_feedback, exam_closed_correct_answer, now(), now()')->where('quiz_id', '=', $quiz_id);
            if (count($sql_quiz_setting->get()) > 0){
                DB::table('el_quiz_educate_plan_setting')->insertUsing(['quiz_id', 'after_test_review_test', 'after_test_yes_no','after_test_score', 'after_test_specific_feedback', 'after_test_general_feedback', 'after_test_correct_answer','exam_closed_review_test', 'exam_closed_yes_no', 'exam_closed_score', 'exam_closed_specific_feedback', 'exam_closed_general_feedback', 'exam_closed_correct_answer', 'created_at', 'updated_at'], $sql_quiz_setting);
            }

            $sql_quiz_teacher = QuizEducatePlanTeacher::selectRaw($quiz_id  .', teacher_id, now(), now()')->where('quiz_id', '=', $quiz_id);
            if (count($sql_quiz_teacher->get()) > 0){
                DB::table('el_quiz_educate_plan_teacher')->insertUsing(['quiz_id', 'teacher_id', 'created_at', 'updated_at'], $sql_quiz_teacher);
            }

        $qwp = QuizEducatePlan::find($quiz_id);
        $qwp->quiz_convert_id = $quiz_educate_plan->id;
        $qwp->status_convert = 1;
        $qwp->save();
        json_message('Chuyển đổi thành công');
    }

}
