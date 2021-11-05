<?php

namespace Modules\Notify\Http\Controllers;

use App\Permission;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifySend;
use Modules\Notify\Entities\RemoveNotifySend;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $notify = NotifySend::getNotifyNew(null,$search,$start_date,$end_date);
        return view('notify::frontend.index', [
            'notify' => $notify
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $profile = Profile::findOrFail(Auth::id());
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $query2 = Notify::query();
        $query2->select([
            'id',
            'subject',
            'content',
            'created_at',
            'url',
            DB::raw('1 AS type')
        ]);
        $query2 = $query2->where('user_id', '=', $profile->user_id);

        $query = NotifySend::query();
        $query->select([
            'a.id',
            'a.subject',
            'a.content',
            'a.created_at',
            'a.url',
            DB::raw('2 AS type')
        ]);
        $query->from('el_notify_send AS a');
        $query->where('a.status', '=', 1);
        if (!Permission::isAdmin()){
            $query->whereNotIn('a.id', function ($sub) use ($profile){
               $sub->select('notify_send_id')
                    ->from('el_remove_notify_send')
                    ->where('user_id', '=', $profile->user_id)
                    ->whereNotNull('id');
            });
            $query->whereIn('a.id', function ($subquery) use ($profile, $unit, $title) {
                $subquery->select(['notify_send_id'])
                    ->from('el_notify_send_object')
                    ->where('status', '=', 1)
                    ->where(function ($sub) use ($profile, $unit, $title) {
                        $sub->orWhere('user_id', '=', $profile->user_id)
                            ->orWhere('title_id', '=', @$title->id)
                            ->orWhere('unit_id', '=', $unit ? @$unit->id : '');
                    });
            });
            $query->orWhere(function ($sub) use ($profile, $unit, $title){
                $sub->whereNotNull('a.time_send')
                    ->where('a.time_send', '<=', date('Y-m-d H:i:s'))
                    ->whereIn('a.id', function ($subquery) use ($profile, $unit, $title) {
                        $subquery->select(['notify_send_id'])
                            ->from('el_notify_send_object')
                            ->where('status', '=', 0)
                            ->where(function ($sub) use ($profile, $unit, $title) {
                                $sub->orWhere('user_id', '=', $profile->user_id)
                                    ->orWhere('title_id', '=', @$title->id)
                                    ->orWhere('unit_id', '=', $unit ? @$unit->id : '');
                            });
                    });
            });
        }

        $query = $query->union($query2);
        $query_sql = $query->toSql();
        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->type == 2){
                $notify = NotifySend::find($row->id);
            }else{
                $notify = Notify::find($row->id);
            }
            $row->check = ($notify->viewed == 1) ? 'color: #000' : '';

            $row->id = $row->id .'_'. $row->type;
            $row->link = route('module.notify.goto', ['url_encode' => \Crypt::encryptString($row->url)]);
            $row->created_at2 = get_date($row->created_at);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function gotoUrl($url_encode) {
        $url_decode = \Crypt::decryptString($url_encode);
        return redirect($url_decode);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        if (!Permission::isAdmin()){
            foreach ($ids as $id){
                $temp = strstr($id, '_');
                $type = str_replace('_', '', $temp);
                $item = str_replace($temp, '', $id);

                if ($type == 2){
                    $model = new RemoveNotifySend();
                    $model->notify_send_id = $item;
                    $model->user_id = Auth::id();
                    $model->save();
                }else{
                    Notify::find($item)->delete();
                }
            }
        }else{
            foreach ($ids as $id){
                $temp = strstr($id, '_');
                $type = str_replace('_', '', $temp);
                $item = str_replace($temp, '', $id);

                if ($type == 2){
                    NotifySend::find($item)->delete();
                }else{
                    Notify::find($item)->delete();
                }
            }
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function view(Request $request, $id, $type){
        $id = $request->id;

        if ($type == 2){
            $notify = NotifySend::find($id);
            $notify->viewed = 1;
            $notify->save();

        }else{
            $notify = Notify::find($id);
            $notify->viewed = 1;
            $notify->save();
        }

        return view('notify::frontend.view', [
            'notify' => $notify
        ]);
    }
}
