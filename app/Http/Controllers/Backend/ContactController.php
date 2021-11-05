<?php

namespace App\Http\Controllers\Backend;

use App\Contact;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index() {
        return view('backend.contact.index');
    }

    public function form($id = null) {
        $model = Contact::firstOrNew(['id' => $id]);
        $page_title = $model->id ? $model->name : trans('backend.add_new');
        return view('backend.contact.form', [
            'model' => $model,
            'page_title' => $page_title
        ]);
    }

    public function getData(Request $request) {
        $search = $request->get('search');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = Contact::query();
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.contact.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        Contact::destroy($ids);
        json_message('Đã xóa thành công');
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'content' => 'required',
        ], $request, Contact::getAttributeName());
        
        $model = Contact::firstOrNew(['id' => $request->id]);
        $model->name = $request->name;
        $model->description = $request->content;        

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.contact')
            ]);
        }
        json_message('Không thể lưu', 'error');
    }
}
