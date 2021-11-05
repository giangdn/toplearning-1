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
use Modules\Notify\Imports\ProfileImport;

class NotifySendController extends Controller
{
    public function index()
    {
        return view('notify::backend.notify_send.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        NotifySend::addGlobalScope(new DraftScope());
        $query = NotifySend::query()->select(['*']);
        if ($search) {
            $query->where('subject', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $profile = Profile::find($row->created_by);

            $row->edit_url = route('module.notify_send.edit', ['id' => $row->id]);
            $row->created_at2 = get_date($row->created_at);
            $row->created_by2 = $profile->lastname . ' ' . $profile->firstname;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'subject' => 'required',
        ], $request, NotifySend::getAttributeName());

        $model = NotifySend::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->time_send = $request->time_send ? date_convert($request->time_send, $request->start_time.":00") : null;
        $model->created_by = \Auth::id();

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.notify_send.edit', ['id' => $model->id])
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function form($id = 0) {
        $errors = session()->get('errors');
        \Session::forget('errors');

        if ($id) {
            $model = NotifySend::find($id);
            $page_title = $model->subject;

            return view('notify::backend.notify_send.form', [
                'model' => $model,
                'page_title' => $page_title
            ]);
        }
        $model =  new NotifySend();
        $page_title = trans('backend.add_new') ;

        return view('notify::backend.notify_send.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        NotifySend::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveObject($notify_send_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'nullable|exists:el_titles,id',
        ], $request);

        $title_id = $request->input('title_id');
        $unit_id = $request->input('unit_id');

        if ($unit_id){
            foreach ($unit_id as $item){
                if (NotifySendObject::checkObjectUnit($notify_send_id, $item)){
                    continue;
                }
                $model = new NotifySendObject();
                $model->notify_send_id = $notify_send_id;
                $model->unit_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm đơn vị thành công',
            ]);
        }else{
            foreach ($title_id as $item){
                if (NotifySendObject::checkObjectTitle($notify_send_id, $item)){
                    continue;
                }
                $model = new NotifySendObject();
                $model->notify_send_id = $notify_send_id;
                $model->title_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm chức danh thành công',
            ]);
        }

    }

    public function getObject($notify_send_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = NotifySendObject::query();
        $query->select(['a.*', 'b.name AS title_name', 'c.name AS unit_name', 'd.code AS profile_code', 'd.lastname', 'd.firstname','e.name as unit_manager']);
        $query->from('el_notify_send_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'c.parent_code');
        $query->leftJoin('el_profile AS d', 'd.user_id', '=', 'a.user_id');
        $query->where('a.notify_send_id', '=', $notify_send_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->send_by = $row->send_by ? Profile::usercode($row->send_by) : '';
            $row->time_send = get_date($row->time_send, 'H:i:s d/m/Y');
            $row->profile_name = $row->lastname . ' ' . $row->firstname;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeObject($notify_send_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Đối tượng',
        ]);

        $item = $request->input('ids');
        NotifySendObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importObject($notify_send_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ProfileImport($notify_send_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.notify_send.edit', ['id' => $notify_send_id]),
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
                $model = NotifySend::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = NotifySend::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function sendObject(Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Đối tượng',
        ]);

        $ids = $request->input('ids', null);
        foreach($ids as $id){
            $obj = NotifySendObject::findOrFail($id);
            $obj->status = 1;
            $obj->time_send = date('Y-m-d H:i:s');
            $obj->send_by = \Auth::id();
            $obj->save();


            $model = NotifySend::where('id', '=', $obj->notify_send_id)
                ->where('status', '=', 1)
                ->first();

            if (empty($model)) {
                continue;
            }

            $profile = Profile::query()
                ->select(['profile.user_id'])
                ->from('el_profile as profile')
                ->leftJoin('el_unit as unit', 'unit.code', '=', 'profile.unit_code')
                ->leftJoin('el_titles as titles', 'titles.code', '=', 'profile.title_code')
                ->where(function ($sub) use ($obj){
                    $sub->orWhere('unit.id', '=', $obj->unit_id);
                    $sub->orWhere('titles.id', '=', $obj->title_id);
                    $sub->orWhere('profile.user_id', '=', $obj->user_id);
                })
                ->get();

            if (empty($profile)) {
                continue;
            }

            $redirect_url = route('module.notify.view', [
                'id' => $model->id,
                'type' => 2
            ]);
            $content = Str::words(html_entity_decode(strip_tags($model->content)), 10);

            $notification = new AppNotification();
            $notification->setTitle($model->subject);
            $notification->setMessage($content);
            $notification->setUrl($redirect_url);

            foreach ($profile as $item){
                //$api = new AppApi($item->user_id);
                //$response = $api->pushNotify($model->subject, $content, $url_app, null);

                $notification->add($item->user_id);
            }

            $notification->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Gửi thành công',
        ]);
    }
}
