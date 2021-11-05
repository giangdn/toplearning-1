<?php

namespace Modules\VirtualClassroom\Http\Controllers;

use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories\TrainingTeacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\VirtualClassroom\Entities\VirtualClassroom;
use Modules\VirtualClassroom\Entities\VirtualClassroomTeacher;
use Modules\VirtualClassroom\Helpers\BBBApi;

class VirtualClassroomController extends Controller
{
    public function index(){
        return view('virtualclassroom::backend.virtual_classroom.index');
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $prefix= \DB::getTablePrefix();
        VirtualClassroom::addGlobalScope(new DraftScope());
        $query = VirtualClassroom::query();
        $query->select('el_virtual_classroom.*',\DB::raw("concat({$prefix}b.lastname,' ',{$prefix}b.firstname) as user_name"));
        $query->leftJoin('el_profile as b', 'b.user_id', '=', 'el_virtual_classroom.created_by');
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('name', 'like', '%' . $search . '%');
                $subquery->orWhere('code', 'like', '%' . $search . '%');
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
            $row->edit_url = route('module.virtualclassroom.edit', ['id' => $row->id]);
            $row->start_date = get_date($row->start_date, 'H:i d/m/Y');
            $row->end_date = get_date($row->end_date, 'H:i d/m/Y');
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null)
    {
        $teachers = TrainingTeacher::get();
        $model = VirtualClassroom::firstOrNew(['id' => $id]);
        $page_title = ($id ? $model->name : trans('backend.add_new'));

        return view('virtualclassroom::backend.virtual_classroom.form', [
            'model' => $model,
            'page_title' => $page_title,
            'teachers' => $teachers
        ]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'code' => 'required|unique:el_virtual_classroom,code,' . $request->id,
            'name' => 'required',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
        ], $request, VirtualClassroom::getAttributeName());

        $start_time = $request->start_time;
        $end_time = $request->end_time;

        $model = VirtualClassroom::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());

        $model->start_date = date_convert($request->input('start_date'), $start_time.':00');
        $model->end_date = date_convert($request->input('end_date'), $end_time.':59');

        if ($model->start_date > $model->end_date) {
            json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
        }

        if (empty($request->id)) {
            if ($model->start_date < date('Y-m-d')) {
                json_message('Ngày bắt đầu tính từ hiện tại', 'error');
            }
        }

        if (empty($model->id)) {
            $model->created_by = \Auth::id();
        }

        $model->updated_by = \Auth::id();
        $model->status = 2;

        if ($model->save()) {

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.virtualclassroom.edit', ['id' => $model->id])
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);

        foreach ($ids as $id){
            $check = OnlineCourseActivity::where('activity_id', '=', 6)
                ->where('subject_id', '=', $id);
            if ($check->exists()){
                continue;
            }

            VirtualClassroomTeacher::where('virtual_classroom_id', $id)->delete();
            VirtualClassroom::find($id)->delete();
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function approve(Request $request)
    {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            $query = VirtualClassroom::find($id);
            $query->status = $status;
            $query->save();
        }

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }

    public function saveTeacher($virtual_id, Request $request){
        $teacher = $request->post('teacher_id', '');

        foreach ($teacher as $item){
            $check = VirtualClassroomTeacher::query()
                ->where('virtual_classroom_id', '=', $virtual_id)
                ->where('teacher_id', $item);

            if ($check->exists()){
                continue;
            }

            $teachers = new VirtualClassroomTeacher();
            $teachers->virtual_classroom_id = $virtual_id;
            $teachers->teacher_id = $item;
            $teachers->save();
        }

        return \response()->json([
            'status' => 'success',
            'message' => 'Thêm giảng viên thành công',
        ]);
    }

    public function getTeacher($virtual_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = VirtualClassroomTeacher::query();
        $query->select([
            'a.*',
            'b.code as teacher_code',
            'b.name as teacher_name',
            'b.email as teacher_email',
            'b.phone as teacher_phone'
        ]);
        $query->from('el_virtual_classroom_teacher AS a')
            ->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id')
            ->where('a.virtual_classroom_id', '=', $virtual_id);

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

    public function removeTeacher($virtual_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Giảng viên',
        ]);

        $item = $request->input('ids');
        VirtualClassroomTeacher::destroy($item);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
