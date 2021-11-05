<?php

namespace App\Http\Controllers\Backend;

use App\Feedback;
use App\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function index() {
        return view('backend.feedback.index');
    }

    public function form($id = null) {
        $model = Feedback::firstOrNew(['id' => $id]);
        $page_title = $model->id ? $model->name : trans('backend.add_new');
        return view('backend.feedback.form', [
            'model' => $model,
            'page_title' => $page_title
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = Feedback::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $profile = $row->updated_by ? Profile::find($row->updated_by) : Profile::find($row->created_by);
            $row->updated_at2 = $row->updated_at ? get_date($row->updated_at) : get_date($row->created_at);
            $row->updated_by2 = $profile->lastname . ' ' . $profile->firstname;
            $row->edit_url = route('backend.feedback.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        Feedback::destroy($ids);
        json_message('Đã xóa thành công');
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'content' => 'required',
            'position' => 'required',
            'star' => 'required',
            'image' => 'required',
        ], $request, Feedback::getAttributeName());

        $model = Feedback::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->image = path_upload($model->image);
        if (empty($model->id)) $model->created_by = \Auth::id();
        $model->updated_by = \Auth::id();

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.feedback')
            ]);
        }

        json_message('Không thể lưu', 'error');

    }
}
