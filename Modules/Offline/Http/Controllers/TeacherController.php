<?php

namespace Modules\Offline\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineTeacher;
use App\Models\Categories\TrainingTeacher;

class TeacherController extends Controller
{
    public function index($course_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $course = OfflineCourse::find($course_id);
        $page_title = $course->name;
        $teachers = TrainingTeacher::get();
        return view('offline::backend.teacher.index', [
            'page_title' => $page_title,
            'course' => $course,
            'teachers' => $teachers,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getData($course_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineTeacher::query();
        $query->select(['a.*', 'b.name as teacher_name', 'b.email as teacher_email', 'b.phone as teacher_phone']);
        $query->from('el_offline_course_teachers AS a');
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

    public function save($course_id, Request $request) {
        $this->validateRequest([
            'teacher_id' => 'required|exists:el_training_teacher,id',
        ], $request, OfflineTeacher::getAttributeName());

        $is_unit = $request->input('unit');

        $teacher_id = $request->input('teacher_id');

        if(OfflineTeacher::checkExists($course_id, $teacher_id)){
            json_message('Giảng viên đã tồn tại', 'error');
        }
        $model = new OfflineTeacher();
        $model->teacher_id = $teacher_id;
        $model->course_id = $course_id;

        if ($model->save()) {

            $redirect = $is_unit > 0 ? route('module.training_unit.offline.teacher', ['id' => $course_id]) : route('module.offline.teacher', ['id' => $course_id]);

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => $redirect,
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove($course_id, Request $request) {
        $ids = $request->input('ids', null);
        OfflineTeacher::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveNote($course_id, Request $request) {
        $this->validateRequest([
            'note' => 'nullable',
            'off_teacher_id' => 'required',
        ], $request);

        $note = $request->input('note');
        $off_teacher_id = $request->input('off_teacher_id');

        $model = OfflineTeacher::find($off_teacher_id);
        $model->note = $note;
        $model->save();
        json_message('ok');
    }

}
