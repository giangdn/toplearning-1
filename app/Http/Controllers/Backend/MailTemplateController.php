<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\MailTemplate;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;

class MailTemplateController extends Controller
{
    public function index() {
        return view('backend.mailtemplate.index');
    }

    public function form($id) {
        $model = MailTemplate::findOrFail($id);
        return view('backend.mailtemplate.form', [
            'model' => $model
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required|max:255',
            'title' => 'required|max:255',
            'content' => 'required',
        ], $request, ['name' => 'Tên', 'title' => 'Tiêu đề', 'content' => 'Nội dung']);
        $model = MailTemplate::findOrFail($request->id);
        $model->fill($request->all());
        $model->content = html_entity_decode($request->content);

        if ($model->save()) {
            json_message('Lưu thành công');
        }

        json_message('Không thể lưu', 'error');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        MailTemplate::addGlobalScope(new DraftScope());
        $query = MailTemplate::query();
        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('title', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.mailtemplate.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
