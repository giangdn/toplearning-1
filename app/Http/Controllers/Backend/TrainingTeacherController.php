<?php
namespace App\Http\Controllers\Backend;

use App\Exports\TrainingTeacherExport;
use App\Imports\ImportTrainingTeacher;
use App\Jobs\NotifyUserOfCompletedImportSubject;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\Unit;
use App\Notifications;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TeacherType;
use App\Profile;
use Illuminate\Support\Str;
use Modules\ReportNew\Entities\ReportNewExportBC11;

class TrainingTeacherController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $notifications = Notifications::where('notifiable_id', '=', \Auth::id())
            ->where('notifiable_type', '=', 'App\User')
            ->whereNull('read_at')
            ->get();
        \Session::forget('errors');

        $teacher_types = TeacherType::get();
        $training_partner = TrainingPartner::get();
        $user_id = \Auth::id();
        $model = Profile::query();
        $model->select(['a.*']);
        $model->from('el_profile as a');
        // $model->where('a.status',1);
        if($user_id != 2) {
            $model->where('profile.user_id', '!=', 2);
            $model->where('profile.user_id', '!=', 1);
            $model->whereNotIn('a.user_id', function($sub) {
                $sub->select(['user_id']);
                $sub->from('el_training_teacher');
                $sub->pluck('user_id')->toArray();
            });
        }
        $profile = $model->get();
        return view('backend.category.training_teacher.index', [
            'notifications' => $notifications,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
            'teacher_types' => $teacher_types,
            'training_partner' => $training_partner,
            'get_users_not_regis' => $profile,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        TrainingTeacher::addGlobalScope(new DraftScope());
        $query = TrainingTeacher::query();
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.training_teacher.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = TrainingTeacher::findOrFail($request->id);
        $user = Profile::find($model->user_id);
        $unit = Unit::where('code', '=', @$user->unit_code)->first();
        $title = Titles::where('code', '=', @$user->title_code)->first();

        json_result([
            'model' => $model,
            'user' => $user,
            'unit' => $unit,
            'title' => $title,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'type' => 'required_if:id,<>,|in:1,2',
            'code' => 'required|unique:el_training_teacher,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
            'phone' => 'required',
        ], $request, TrainingTeacher::getAttributeName());

        $model = TrainingTeacher::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $name = $request->input('name');

        if(TrainingTeacher::checkExists($name)){
            TrainingTeacher::where('name', '=', $name)
            ->where('type', '=', $request->input('type'))
            ->update([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'status' => $request->input('status'),
            ]);
        }

        if ($model->save()) {

            $report11 = ReportNewExportBC11::query()->where('training_teacher_id', $model->id);
            if ($report11->exists()){
                $report11->update([
                    'user_id' => $model->user_id,
                    'user_code' => $model->code,
                    'fullname' => $model->name,
                    'account_number' => $model->account_number
                ]);
            }

            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
            ]);
        }

        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        TrainingTeacher::destroy($ids);
        ReportNewExportBC11::query()->whereIn('training_teacher_id', $ids)->delete();
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxGetUser(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request);

        $ids = $request->input('ids');

        $user = Profile::where('user_id', '=', $ids)->first();
        $unit = Unit::where('code', '=', @$user->unit_code)->first();
        $title = Titles::where('code', '=', @$user->title_code)->first();

        json_result([
            'code' => $user->code,
            'name' => $user->lastname . ' ' . $user->firstname,
            'phone' => $user->phone,
            'email' => $user->email,
            'unit' => @$unit->code . ' ' . @$unit->name,
            'title' => @$title->code . ' ' . @$title->name,
        ]);
    }

    public function import(Request $request) {
        $this->validateRequest([
            'import_file' => 'required|file',
        ], $request, [
            'import_file' => ''
        ]);

        $file = $request->file('import_file');
        $name = 'import_subject_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new ImportTrainingTeacher(\Auth::user()))->queue($newfile)->chain([
                new NotifyUserOfCompletedImportSubject(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('backend.category.training_teacher')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
            'redirect' => route('backend.category.training_teacher')
        ]);
    }

    public function export()
    {
        return (new TrainingTeacherExport())->download('danh_sach_giang_vien_'. date('d_m_Y') .'.xlsx');
    }
}
