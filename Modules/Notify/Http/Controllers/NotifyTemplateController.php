<?php

namespace Modules\Notify\Http\Controllers;

use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\NotifySend;
use Modules\Notify\Entities\NotifySendObject;
use Modules\Notify\Entities\NotifyTemplate;
use Modules\Notify\Imports\ProfileImport;

class NotifyTemplateController extends Controller
{
    public function index() {
        return view('notify::backend.notify_template.index');
    }

    public function form($id) {
        $model = NotifyTemplate::findOrFail($id);
        return view('notify::backend.notify_template.form', [
            'model' => $model
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required|max:255',
            'title' => 'required|max:255',
            'content' => 'required',
        ], $request, ['name' => 'Tên', 'title' => 'Tiêu đề', 'content' => 'Nội dung']);

        $model = NotifyTemplate::findOrFail($request->id);
        $model->fill($request->all());

        if ($model->save()) {
            json_message(trans('lageneral.successful_save'));
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        NotifyTemplate::addGlobalScope(new DraftScope());
        $query = NotifyTemplate::query();
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
            $row->edit_url = route('module.notify.template.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
