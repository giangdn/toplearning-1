<?php

namespace Modules\User\Http\Controllers\Frontend;

use App\Permission;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifySend;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineObject;

class DashBoardController extends Controller
{
    public function index()
    {
        $profile = Profile::find(Auth::id());
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $online_object = OnlineCourse::select(['id', 'code', 'name', 'start_date', 'end_date', 'register_deadline'])
            ->whereNotIn('id', function ($subquery) use ($profile){
                $subquery->select(['course_id'])
                    ->from('el_online_register')
                    ->where('user_id', '=', $profile->user_id);
            })
            ->where('status', '=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'online_object');

        $online_register = OnlineCourse::select(['id', 'code', 'name', 'start_date', 'end_date', 'register_deadline'])
            ->whereIn('id', function ($subquery) use ($profile){
                $subquery->select(['course_id'])
                    ->from('el_online_register')
                    ->where('user_id', '=', $profile->user_id);
            })
            ->where('status', '=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'online_register');

        $offline_object = OfflineCourse::select(['id', 'code', 'name', 'start_date', 'end_date', 'register_deadline'])
            ->whereNotIn('id', function ($subquery) use ($profile){
                $subquery->select(['course_id'])
                    ->from('el_offline_register')
                    ->where('user_id', '=', $profile->user_id);
            })
            ->where('status', '=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'offline_object');

        $offline_register = OfflineCourse::select(['id', 'code', 'name', 'start_date', 'end_date', 'register_deadline'])
            ->whereIn('id', function ($subquery) use ($profile){
                $subquery->select(['course_id'])
                    ->from('el_offline_register')
                    ->where('user_id', '=', $profile->user_id);
            })
            ->where('status', '=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'offline_register');

        //Thông báo
        $query2 = Notify::query();
        $query2->select([
            'id',
            'subject',
            'content',
            'created_at',
            'url',
            \DB::raw('1 AS type')
        ]);
        $query2 = $query2->where('user_id', '=', $profile->user_id);

        $query = NotifySend::query();
        $query->select([
            'a.id',
            'a.subject',
            'a.content',
            'a.created_at',
            'a.url',
            \DB::raw('2 AS type')
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
                    ->orWhere('user_id', '=', $profile->user_id)
                    ->orWhere('title_id', '=', $title->id)
                    ->orWhere('unit_id', '=', $unit ? $unit->id : '');
            });
        }

        $query = $query->union($query2);
        $query_sql = $query->toSql();
        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());

        $messages = $query->get();

        return view('user::frontend.dashboard.index', [
            'online_object' => $online_object,
            'online_register' => $online_register,
            'offline_object' => $offline_object,
            'offline_register' => $offline_register,
            'messages' => $messages
        ]);
    }
}
