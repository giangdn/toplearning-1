<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Subject;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingProgram;
use App\Exports\TrainingProgramExport;
use App\Imports\TrainingProgramImport;

class TrainingProgramController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        \Session::forget('errors');
        return view('backend.category.training_program.index',[
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
        TrainingProgram::addGlobalScope(new DraftScope());
        $query = TrainingProgram::query();
        if ($search) {
            $query->orWhere('code', 'like', '%'. $search .'%');
            $query->orWhere('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = TrainingProgram::select(['id','status','code','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_training_program,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1'
        ], $request, TrainingProgram::getAttributeName());

        $model = TrainingProgram::firstOrNew(['id' => $request->id]);
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
        $related = Subject::whereIn('training_program_id', $ids)->first();
        if ($related){
            json_message('Có dữ liệu liên quan. Không thể xoá', 'error');
        }
        TrainingProgram::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function export(){
        return (new TrainingProgramExport())->download('danh_sach_chuong_trinh_dao_tao_'. date('d_m_Y') .'.xlsx');
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new TrainingProgramImport();
        \Excel::import($import, $request->file('import_file'));
        
        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('backend.category.training_program')
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
                $model = TrainingProgram::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = TrainingProgram::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
