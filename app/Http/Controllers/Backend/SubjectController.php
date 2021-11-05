<?php
namespace App\Http\Controllers\Backend;

use App\Exports\SubjectExport;
use App\Imports\ImportSubject;
use App\Jobs\NotifyUserOfCompletedImportSubject;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Unit;
use App\Notifications;
use App\Models\Categories\Subject;
use App\Models\Categories\SubjectConditions;
use App\Models\Categories\TrainingProgram;
use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\Capabilities\Entities\CapabilitiesTitleSubject;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class SubjectController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $notifications = Notifications::where('notifiable_id', '=', \Auth::id())
            ->where('notifiable_type', '=', 'App\User')
            ->whereNull('read_at')
            ->get();
        
        $level_subject = LevelSubject::all();

        return view('backend.category.subject.index', [
            'notifications' => $notifications,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
            'level_subject' => $level_subject,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        Subject::addGlobalScope(new DraftScope());
        $query = Subject::query();
        $query->select([
            'el_subject.*',
            'b.name AS parent_name',
            'c.name as level_subject_name',
        ]);
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_subject.training_program_id');
        $query->leftJoin('el_level_subject as c', 'c.id', '=', 'el_subject.level_subject_id');

        if ($search) {
            $query->orWhere('el_subject.code', 'like', '%'. $search .'%');
            $query->orWhere('el_subject.name', 'like', '%'. $search .'%');
        }

        if ($training_program_id) {
            $query->where('el_subject.training_program_id', '=', $training_program_id);
        }

        if ($level_subject_id){
            $query->where('el_subject.level_subject_id', '=', $level_subject_id);
        }

        $count = $query->count();
        $query->orderBy('el_subject.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.subject.edit', ['id' => $row->id]);
            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = Subject::findOrFail($request->id);
        $training_programs = TrainingProgram::find($model->training_program_id);
        $profile = Profile::find($model->created_by);
        $unit = Unit::find($model->unit_id);

        $format_date = get_date($model->created_date);
        // dd($unit_managers);
        json_result([
            'model' => $model,
            'training_programs' => $training_programs,
            'profile' => $profile,
            'unit' => $unit,
            'format_date' => $format_date,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_subject,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
            'training_program_id' => 'required|exists:el_training_program,id',
            'level_subject_id' => 'required|exists:el_level_subject,id',
        ], $request, Subject::getAttributeName());

        $model = Subject::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->created_date = $request->created_date ? date_convert($request->created_date) : null;
        $model->created_by = $model->created_by ? $model->created_by : \Auth::id();

        if ($request->id){
            $subject_code = $request->code;
            $get_subject_code = Subject::find($request->id);
            $check_subject_code_online = OnlineCourse::where('subject_id',$request->id)->get();
            $check_subject_code_offline = OfflineCourse::where('subject_id',$request->id)->get();
            if( (!$check_subject_code_online->isEmpty() || !$check_subject_code_offline->isEmpty()) && $get_subject_code->code != $subject_code ) {
                json_result([
                    'status' => 'warning',
                    'message' => 'Ko thể lưu vì mã khóa học đã được sử dụng. Vui lòng ko thay đổi mã khóa học',
                ]);
            }
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                
            ]);
        }

        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        Subject::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
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
            (new ImportSubject(\Auth::user()))->queue($newfile)->chain([
                new NotifyUserOfCompletedImportSubject(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('backend.category.subject')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
            'redirect' => route('backend.category.subject')
        ]);
    }
    public function export()
    {
        return (new SubjectExport())->download('danh_sach_tai_lieu_'. date('d_m_Y') .'.xlsx');
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Khóa học',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Subject::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Subject::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
