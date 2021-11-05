<?php

namespace Modules\Notify\Entities;

use App\BaseModel;
use App\Permission;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NotifySend extends BaseModel
{
    protected $table = 'el_notify_send';
    protected $fillable = [
        'subject',
        'content',
        'url',
        'type',
        'popup',
        'popup_type',
        'popup_image',
        'created_by',
        'status',
        'type',
        'important',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName(){
        return [
            'subject' => 'Tiêu đề thông báo',
            'url' => 'Liên kết',
            'created_by' => trans('lageneral.creator'),
            'status' => 'Trạng thái',
        ];
    }

    public static function countMessage(){
        $count = 0;
        $profile = Profile::find(Auth::id());
        if ($profile){
            $title = Titles::firstOrNew(['code' => $profile->title_code]);
            $unit = Unit::firstOrNew(['code' => $profile->unit_code]);

            $query2 = Notify::query();
            $query2->select([
                'id',
                'subject',
                'content',
                'created_at',
                'url'
            ]);
            $query2 = $query2->where('viewed', '=', 0);
            $query2 = $query2->where('user_id', '=', $profile->user_id);

            $query = NotifySend::query();
            $query->select([
                'a.id',
                'a.subject',
                'a.content',
                'a.created_at',
                'a.url'
            ]);
            $query->from('el_notify_send AS a');
            $query->where('a.status', '=', 1);
            $query->where('a.viewed', '=', 0);
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

            $count += $query->count();
        }

        return  $count;
    }

    public static function getNotifyNew($limit = null,$search = null,$start_date = null,$end_date = null){
        $profile = Profile::findOrFail(Auth::id());
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $query2 = Notify::query();
        $query2->select([
            'id',
            'subject',
            'content',
            'created_at',
            'viewed',
            'url',
            'important',
            \DB::raw('1 AS type')
        ]);
        $query2 = $query2->where('user_id', '=', $profile->user_id);
        if ($search){
            $query2->where('subject', 'like', '%'. $search .'%');
        }
        if ($start_date) {
            $query2->where('created_at', '>=', date_convert($start_date, '00:00:00'));
        }

        if ($end_date) {
            $query2->where('created_at', '<=', date_convert($end_date, '23:59:59'));
        }

        $query = NotifySend::query();
        $query->select([
            'a.id',
            'a.subject',
            'a.content',
            'a.created_at',
            'a.viewed',
            'a.url',
            'a.important',
            \DB::raw('2 AS type')
        ]);
        $query->from('el_notify_send AS a');
        $query->where('a.status', '=', 1);

        if ($search){
            $query->where('subject', 'like', '%'. $search .'%');
        }
        if ($start_date) {
            $query->where('created_at', '>=', date_convert($start_date, '00:00:00'));
        }

        if ($end_date) {
            $query->where('created_at', '<=', date_convert($end_date, '23:59:59'));
        }

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
        $query->orderByDesc('created_at');
        
        if ($limit){
            $query->limit($limit);
        }
        return $query->get();
    }
}
