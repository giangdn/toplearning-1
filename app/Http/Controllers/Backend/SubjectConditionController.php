<?php
namespace App\Http\Controllers\Backend;

use App\Models\Categories\SubjectConditions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectConditionController extends Controller
{
    public function index() {
        return view('backend.category.subject_conditions.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SubjectConditions::query();
        $query->from('el_subject_conditions');

        if ($search) {
            $query->where('code', 'like', '%'. $search .'%');
            $query->orWhere('name', 'like', '%'. $search .'%');
            $query->orWhere('name_en', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.subject_conditions.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        if ($id) {
            $model = SubjectConditions::find($id);
            $page_title = data_locale($model->name, $model->name_en);
        }
        else {
            $model = new SubjectConditions();
            $page_title = trans('backend.add_new');
        }

        return view('backend.category.subject_conditions.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_subject_conditions,code,'. $request->id,
            'name' => 'required',
            'name_en' => 'required',
            'status' => 'required|in:0,1',
        ], $request, SubjectConditions::getAttributeName());

        $model = SubjectConditions::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            if ($request->id){
                json_result([
                    'status' => 'success',
                    'message' => 'Lưu thành công',
                    'redirect' => route('backend.category.subject_conditions.edit', [
                        'id' => $model->id
                    ])
                ]);
            }else{
                json_result([
                    'status' => 'success',
                    'message' => 'Lưu thành công',
                    'redirect' => route('backend.category.subject_conditions.create')
                ]);
            }
        }

        json_message('Không thể lưu', 'error');
    }
}
