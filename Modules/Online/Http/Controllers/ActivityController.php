<?php

namespace Modules\Online\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Online\Entities\OnlineActivity;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityFile;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseActivityUrl;
use Modules\Online\Entities\OnlineCourseActivityVideo;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineHistoryEdit;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\Scorm;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\VirtualClassroom\Entities\VirtualClassroom;

class ActivityController extends Controller
{
    public function saveActivity($course_id, $activity_id, Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'select_lesson_name' => 'required',
        ], $request, [
            'name' => 'Tên hoạt động',
            'select_lesson_name' => 'Tên bài học',
        ]);

        $subject_id = $request->post('subject_id');
        $activity = OnlineActivity::findOrFail($activity_id);
        $namespace = 'Modules\Online\Http\Controllers\ActivityController';

        if (method_exists($namespace, 'addActivity'. ucfirst($activity->code))) {
            $subject_id = $this->{'addActivity'. ucfirst($activity->code)}($course_id, $request);
        }

        if ($subject_id) {
            $model = OnlineCourseActivity::firstOrNew(['id' => $request->post('id')]);
            $model->fill($request->all());

            if (!$request->setting_score_course_activity_id && ($request->setting_min_score || $request->setting_max_score)){
                json_message('Chọn hoạt động trước khi thiết lập điểm', 'error');
            }

            if ($request->setting_score_course_activity_id && !$request->setting_min_score && !$request->setting_max_score){
                json_message('Mời nhập điểm thiết lập cho hoạt động', 'error');
            }

            if ($request->setting_start_date){
                $setting_start_date = get_date($request->setting_start_date);
                $setting_start_time = get_date($request->setting_start_date, "H:i:s");

                $model->setting_start_date = date_convert($setting_start_date, $setting_start_time);
            }
            if ($request->setting_end_date){
                $setting_end_date = get_date($request->setting_end_date);
                $setting_end_time = get_date($request->setting_end_date, "H:i:s");

                $model->setting_end_date = date_convert($setting_end_date, $setting_end_time);
            }

            if (empty($model->id)) {
                $acti = OnlineCourseActivity::where('course_id', '=', $course_id)
                    ->where('activity_id', '=', 2)
                    ->where('subject_id', '=', $subject_id)
                    ->first();

                if ($acti && $activity_id == 2){
                    json_message('Hoạt động thi đã thêm. Mời chọn kỳ thi khác', 'error');
                }

                $num_order = (int) OnlineCourseActivity::where('course_id', '=', $course_id)
                        ->first(\DB::raw('MAX(num_order) AS max_num'))->max_num + 1;

                $model->name = $request->name;
                $model->course_id = $course_id;
                $model->activity_id = $activity_id;
                $model->subject_id = $subject_id;
                $model->num_order = $num_order;
                $model->lesson_id = $request->select_lesson_name;
                $model->status = 1;

                if ($model->save()) {

                    if ($model->subject_id && $model->activity_id == 2){
                        Quiz::where('id','=',$model->subject_id)
                            ->update(['course_id'=>$model->course_id,'course_type'=>1]);

                        $course_register = OnlineRegister::whereCourseId($course_id)->where('status', '=', 1)->get();
                        if ($course_register->count() > 0){
                            $quiz_part = QuizPart::where('quiz_id', '=', $model->subject_id)->first();
                            foreach ($course_register as $register){
                                QuizRegister::query()
                                    ->updateOrCreate([
                                        'quiz_id' => $model->subject_id,
                                        'user_id' => $register->user_id,
                                        'type' => $register->user_type,
                                        'part_id' => $quiz_part->id,
                                    ]);
                            }
                        }
                    }

                    if ($model->subject_id && $model->activity_id == 6){
                        VirtualClassroom::where('id','=',$model->subject_id)
                            ->update(['course_id' => $model->course_id]);
                    }

                    $history_edit = new OnlineHistoryEdit();
                    $history_edit->type = 1;
                    $history_edit->course_id = $course_id;
                    $history_edit->user_id = \Auth::id();
                    $history_edit->tab_edit = 'Thêm hoạt động: '. $model->name;
                    $history_edit->ip_address = $request->ip();
                    $history_edit->save();

                    json_result([
                        'status' => 'success',
                        'message' => trans('lageneral.successful_save'),
                        'redirect' => route('module.online.edit', ['id' => $course_id]) . '?tabs=activity'
                    ]);
                }
            }
            else {
                $model->name = $request->post('name');
                $model->subject_id = $subject_id;
                $model->status = 1;
                if ($model->save()) {

                    /*update khóa học kỳ thi */
                    if ($model->subject_id && $model->activity_id == 2){
                        Quiz::where('id','=',$model->subject_id)
                            ->update(['course_id'=>$model->course_id, 'course_type'=>1]);

                        $course_register = OnlineRegister::whereCourseId($course_id)->where('status', '=', 1)->get();
                        if ($course_register->count() > 0){
                            $quiz_part = QuizPart::where('quiz_id', '=', $model->subject_id)->first();
                            foreach ($course_register as $register){
                                QuizRegister::query()
                                    ->updateOrCreate([
                                        'quiz_id' => $model->subject_id,
                                        'user_id' => $register->user_id,
                                        'type' => $register->user_type,
                                        'part_id' => $quiz_part->id,
                                    ]);
                            }
                        }
                    }

                    if ($model->subject_id && $model->activity_id == 6){
                        VirtualClassroom::where('id','=',$model->subject_id)
                            ->update(['course_id' => $model->course_id]);
                    }

                    $history_edit = new OnlineHistoryEdit();
                    $history_edit->course_id = $course_id;
                    $history_edit->user_id = \Auth::id();
                    $history_edit->tab_edit = 'Sửa các hoạt động';
                    $history_edit->ip_address = $request->ip();
                    $history_edit->type = 1;
                    $history_edit->save();

                    json_result([
                        'status' => 'success',
                        'message' => trans('lageneral.successful_save'),
                        'redirect' => route('module.online.edit', ['id' => $course_id]) . '?tabs=activity'
                    ]);
                }
            }
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function addActivityVideo($course_id, Request $request) {
        $model = OnlineCourseActivityVideo::firstOrNew(['id' => $request->subject_id]);
        $model->path = path_upload($request->path);
        $model->extension = pathinfo($request->path, PATHINFO_EXTENSION);
        $model->description = $request->description;
        $model->course_id = $course_id;

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }

    public function addActivityFile($course_id, Request $request) {
        $model = OnlineCourseActivityFile::firstOrNew(['id' => $request->subject_id]);
        $model->path = path_upload($request->path);
        $model->extension = pathinfo($request->path, PATHINFO_EXTENSION);
        $model->description = $request->description;
        $model->course_id = $course_id;

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }

    public function addActivityUrl($course_id, Request $request) {
        $this->validateRequest([
            'url' => 'required|string',
        ], $request, [
            'url' => 'Url',
        ]);

        $model = OnlineCourseActivityUrl::firstOrNew([
            'id' => $request->input('subject_id')
        ]);
        $model->fill($request->all());
        $model->course_id = $course_id;

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }

    public function addActivityScorm($course_id, Request $request) {
        $this->validateRequest([
            'path' => 'required|string|max:150',
        ], $request, [
            'path' => 'Scorm',
        ]);

        $scorm_path = path_upload($request->post('path'));
        $scorm = Scorm::firstOrCreate([
            'origin_path' => $scorm_path,
        ]);

        $model = OnlineCourseActivityScorm::firstOrNew([
            'id' => $request->input('subject_id')
        ]);

        $model->fill($request->all());
        $model->path = $scorm_path;
        $model->status_passed = $request->status_passed ? $request->status_passed : 0;
        $model->status_completed = $request->status_completed ? $request->status_completed : 0;
        $model->course_id = $course_id;
        $model->scorm_id = $scorm->id;

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }

    public function modalAddActivity($course_id) {
        $course = OnlineCourse::findOrFail($course_id);
        $activities = OnlineActivity::where('code', '!=', 'virtualclassroom')->get();

        return view('online::modal.add_activity', [
            'course' => $course,
            'activities' => $activities,
        ]);
    }

    public function modalActivity($course_id, Request $request) {
        $this->validateRequest([
            'activity' => 'required'
        ], $request);

        $subject_id = $request->input('subject_id');
        $activity = $request->input('activity');

        $course = OnlineCourse::findOrFail($course_id);
        $model = OnlineCourseActivity::firstOrNew(['id' => $request->post('id', null)]);
        $module_class = 'Modules\Online\Entities\OnlineCourseActivity'. ucfirst($activity);
        $module = class_exists($module_class) ? $module_class::firstOrNew(['id' => $subject_id]) : null;

        $model_other = OnlineCourseActivity::whereCourseId($course_id)->where('id', '!=', $request->post('id', null))->get();

        return view('online::modal.add_'. $activity .'_activity', [
            'course' => $course,
            'model' => $model,
            'module' => $module,
            'subject_id' => $subject_id,
            'model_other' => $model_other
        ]);
    }

    public function updateNumOrder($course_id, Request $request) {
        $this->validateRequest([
            'num_order' => 'required'
        ], $request);

        $num_orders = $request->num_order;
        foreach ($num_orders as $index => $num_order) {
            OnlineCourseActivity::where('course_id', '=', $course_id)
                ->where('id', '=', $num_order)
                ->update([
                    'num_order' => ($index+1)
                ]);
        }

        json_message('ok');
    }

    public function remove($course_id, Request $request) {
        $this->validateRequest([
            'id' => 'required'
        ], $request);

        $condition = OnlineCourseCondition::whereCourseId($course_id)->first();
        $activity_condition = $condition ? explode(',', $condition->activity) : [];

        if (in_array($request->id, $activity_condition)){
            json_message('Hoạt động đang thiết lập hoàn thành', 'error');
        }

        $check = OnlineCourseActivity::where('course_id', '=', $course_id)
            ->where('id', '=', $request->id)->first();
        /*if ($check->activity_id == 2){
            Quiz::where('course_id', '=', $course_id)
                ->where('course_type', '=', 1)
                ->where('id', '=', $check->subject_id)
                ->update([
                    'course_id' => 0,
                    'course_type' => 0,
                ]);
        }*/
        if ($check->activity_id == 6){
            VirtualClassroom::where('course_id', '=', $course_id)
                ->where('id', '=', $check->subject_id)
                ->update([
                    'course_id' => 0,
                ]);
        }
        $check->delete();

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = \Auth::id();
        $history_edit->tab_edit = 'Xoá các hoạt động';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 1;
        $history_edit->save();

        json_message('ok');
    }

    public function updateStatusActivity($course_id, Request $request) {
        $this->validateRequest([
            'id' => 'required'
        ], $request);
        $status = $request->status;

        OnlineCourseActivity::where('course_id', '=', $course_id)
            ->where('id', '=', $request->id)
            ->update([
                'status' => $status,
            ]);

        if ($status == 0){
            $condition = OnlineCourseCondition::where('course_id', '=', $course_id)->first();
            if ($condition && $condition->activity){
                $activity = explode(',', $condition->activity);
                if (array_search($request->id, $activity) !== false){
                    unset($activity[$request->id - 1]);
                }
                $condition->activity = implode(',', $activity);
                $condition->save();
            }
        }

        $history_edit = new OnlineHistoryEdit();
        $history_edit->type = 1;
        $history_edit->course_id = $course_id;
        $history_edit->user_id = \Auth::id();
        $history_edit->tab_edit = 'Thay đổi trạng thái các hoạt động';
        $history_edit->ip_address = \request()->ip();
        $history_edit->save();

        json_message('ok');
    }

    public function getUrlEditScorm($course_id, Request $request) {
        $this->validateRequest(['subject' => 'required'], $request);
        $course = OnlineCourse::findOrFail($course_id);
        json_result([
            'status' => 'success',
            'edit_url' => get_link_to_moodle('/course/modedit.php?update='. $request->subject .'&return=0&sr=0&lang=vi', 1, $course->moodlecourseid),
        ]);
    }

    public function loadData($course_id, $func, Request $request) {
        if ($func) {
            if (method_exists('Modules\Online\Http\Controllers\ActivityController', $func)) {
                $this->{$func}($course_id, $request);
                exit();
            }
        }

        json_message('Yêu cầu không hợp lệ', 'error');
    }

    protected function loadQuiz($course_id, Request $request) {
        $search = $request->input('search');
        $query = Quiz::query();
        $query->select(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1)
            ->where('quiz_type', '=', 1)
            ->where(function($where) use ($course_id){
                $where->orWhereNull('course_id');
                $where->orWhere('course_id', '=', 0);
                $where->orWhere('course_id', '=', $course_id);
            });

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    protected function loadBBB($course_id, Request $request)
    {
        $search = $request->search;
        $query = VirtualClassroom::query();
        $query->select(
            \DB::raw('id, CONCAT(code, \' - \', name, \' (\', DATE_FORMAT(start_date, \'%H:%i %d/%c/%Y\'), \' - \', DATE_FORMAT(end_date, \'%H:%i %d/%c/%Y\'), \') \') AS text')
        );
        $query->where('start_date', '>=', date('Y-m-d H:i:s'));
        $query->where('status', '=', 1)
            ->where(function($where) use ($course_id){
                $where->orWhere('course_id', '=', 0);
                $where->orWhere('course_id', '=', $course_id);
            });

        if ($search) {
            $query->orWhere('code', 'like', '%'. $search .'%');
            $query->orWhere('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
}
