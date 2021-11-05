<?php
namespace App\Http\Controllers\Backend;

use App\Exports\LevelSubjectExport;
use App\Imports\ImportSubject;
use App\Jobs\NotifyUserOfCompletedImportSubject;
use App\Models\Categories\LevelSubject;
use App\Notifications;
use App\Models\Categories\TrainingProgram;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\Capabilities\Entities\CapabilitiesTitleSubject;
use App\Imports\LevelSubjectImport;

class LevelSubjectController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        \Session::forget('errors');
        return view('backend.category.level_subject.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        LevelSubject::addGlobalScope(new DraftScope());
        $query = LevelSubject::query();
        $query->select(['el_level_subject.*', 'b.name AS parent_name']);
        $query->from('el_level_subject');
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_level_subject.training_program_id');

        if ($search) {
            $query->orWhere('el_level_subject.code', 'like', '%'. $search .'%');
            $query->orWhere('el_level_subject.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('el_level_subject.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.level_subject.edit', ['id' => $row->id]);
            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = LevelSubject::select(['id','status','code','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_level_subject,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ], $request, LevelSubject::getAttributeName());

        $model = LevelSubject::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = \Auth::id();

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

        LevelSubject::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
    public function export()
    {
        return (new LevelSubjectExport())->download('danh_sach_cap_do_'. date('d_m_Y') .'.xlsx');
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new LevelSubjectImport();
        \Excel::import($import, $request->file('import_file'));
        
        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('backend.category.level_subject')
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = LevelSubject::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = LevelSubject::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
