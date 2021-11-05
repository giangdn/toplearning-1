<?php

namespace Modules\TrainingUnit\Http\Controllers;

use App\Models\Categories\Course;
use App\Permission;
use App\Profile;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\Categories\UnitPermission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlinePermission;

class OnlineController extends Controller
{
    public function index()
    {
        $course_categories = CourseCategories::getCourseCategoriesParent(0,null,1);
        return view('trainingunit::backend.online.index', [
            'course_categories' => $course_categories,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $category_id = $request->input('category_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $managers = Permission::getIdUnitManagerByUser('module.training_unit');
        $query = OnlineCourse::query();
        $query->select([
            'a.*',
            'b.name AS category_name',
            'c.name AS subject_name',
            'd.name AS unit_name'
        ]);
        $query->from('el_online_course AS a');
        $query->leftJoin('el_course_categories AS b', 'b.id', '=', 'a.category_id');
        $query->leftJoin('el_subject AS c', 'c.id', '=', 'a.subject_id');
        $query->leftJoin('el_unit AS d', 'd.id', '=', 'a.unit_id');
        $query->whereNotNull('a.unit_id');

        if ($managers) {
            $query->whereIn('a.unit_id', $managers);
        }

        if ($search) {
            $query->where('a.name', 'like', '%'. $search .'%');
        }

        if ($category_id) {
            $query->where('b.id', '=', $category_id);
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
            $row->edit_url = route('module.training_unit.online.edit', ['id' => $row->id]);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');

            $row->register_url = '';
            if (OnlinePermission::viewRegister($row)) {
                $row->register_url = route('module.training_unit.online.register', ['id' => $row->id]);
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $controller = new \Modules\Online\Http\Controllers\BackendController();
        return $controller->form($id);
    }

    public function register($course_id) {
        $controller = new \Modules\Online\Http\Controllers\RegisterController();
        return $controller->index($course_id);
    }

    public function registerForm($course_id) {
        $controller = new \Modules\Online\Http\Controllers\RegisterController();
        return $controller->form($course_id);
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
            $model = OnlineCourse::findOrFail($id);
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
            $query = OnlineCourse::query();
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
        $ids = $request->ids;
        OnlineCourse::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

}
