<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Certificate;

class CertController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        return view('backend.category.cert.index',[
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
        Certificate::addGlobalScope(new DraftScope());
        $query = Certificate::query();

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('certificate_code', 'like', '%'. $search .'%');
                $sub_query->orWhere('certificate_name', 'like', '%'. $search .'%');
            });
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
        $model = Certificate::select(['id','certificate_code','certificate_name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'certificate_code' => 'required',
            'certificate_name' => 'required',
        ], $request, Certificate::getAttributeName());

        $model = Certificate::find($request->id);
        if ($model){
            $model->certificate_code = $request->input('certificate_code');
            $model->certificate_name = $request->input('certificate_name');
            $model->updated_by = \Auth::id();
            if ($request->id) {
                $model->created_by = $model->created_by;
            }
            $model->save();

            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.category.cert.edit', [
                    'id' => $model->id
                ])
            ]);
        }

        $exists = Certificate::where('certificate_code', '=', $request->input('certificate_code'))->exists();

        if ($exists){
            json_message('Mã trình độ đã tồn tại', 'error');
        }

        $model = new Certificate();
        $model->certificate_code = $request->input('certificate_code');
        $model->certificate_name = $request->input('certificate_name');
        $model->updated_by = \Auth::id();
        $model->save();

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        Certificate::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
