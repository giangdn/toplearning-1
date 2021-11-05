<?php

namespace Modules\TrainingUnit\Http\Controllers;

use App\Models\Categories\Course;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\Profile;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflinePermission;

class OfflineController extends Controller
{
    public function index() {
        $training_program = TrainingProgram::where('status', '=', 1)->get();
        $subject = Subject::where('status', '=', 1)->get();

        return view('trainingunit::backend.offline.index',[
            'training_program' => $training_program,
            'subject' => $subject
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $training_program_id = $request->input('training_program_id');
        $subject_id = $request->input('subject_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $managers = UnitManager::getIdUnitManagedByUser(Auth::id());

        $query = OfflineCourse::query();
        $query->select([
            'a.*',
            'c.name AS subject_name',
        ]);
        $query->from('el_offline_course AS a');
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'a.training_program_id');
        $query->leftJoin('el_subject AS c', 'c.id', '=', 'a.subject_id');
        $query->whereNotNull('a.unit_id');

        if ($managers) {
            $query->whereIn('a.unit_id', $managers);
        }

        if ($search) {
            $query->where('a.name', 'like', '%'. $search .'%');
        }
        if ($training_program_id){
            $query->where('b.id', '=', $training_program_id);
        }
        if ($subject_id){
            $query->where('c.id', '=', $subject_id);
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
            $row->edit_url = route('module.training_unit.offline.edit', ['id' => $row->id]);
            $row->register_url = '';
            if (OfflinePermission::viewRegister($row)) {
                $row->register_url = route('module.training_unit.offline.register', ['id' => $row->id]);
            }

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        //$profile = Profile::find(Auth::id());
        //$unit = Unit::where('code', '=', $profile->unit_code)->first();

        $controller = new \Modules\Offline\Http\Controllers\BackendController();
        $controller->is_unit = 1;
        return $controller->form($id);
    }

    public function register($course_id) {
        $controller = new \Modules\Offline\Http\Controllers\RegisterController();
        return $controller->index($course_id);
    }

    public function registerForm($course_id) {
        $controller = new \Modules\Offline\Http\Controllers\RegisterController();
        return $controller->form($course_id);
    }

    public function teacher($course_id) {
        $controller = new \Modules\Offline\Http\Controllers\TeacherController();
        return $controller->index($course_id);
    }

    public function attendance($course_id, Request $request) {
        $controller = new \Modules\Offline\Http\Controllers\AttendanceController();
        return $controller->index($course_id, $request);
    }

    public function result($course_id, Request $request) {
        $controller = new \Modules\Offline\Http\Controllers\ResultController();
        return $controller->index($course_id, $request);
    }

    public function ajaxIsopenPublish(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Offline',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach($ids as $id){
            $model = OfflineCourse::findOrFail($id);
            $model->isopen = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function approve(Request $request) {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            $query = OfflineCourse::query();
            $query->where('id', $id);
            $query->update(['status' => $status]);
        }

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }

    public function remove(Request $request) {
        $ids = $request->ids;dd($ids);
        OfflineCourse::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

}
