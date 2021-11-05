<?php

namespace Modules\Notify\Entities;

use Illuminate\Database\Eloquent\Model;

class NotifySendObject extends Model
{
    protected $table = 'el_notify_send_object';
    protected $fillable = [
        'notify_send_id',
        'unit_id',
        'title_id',
        'user_id',
        'status'
    ];
    protected $primaryKey = 'id';

    public $timestamps = null;

    public static function checkObjectUnit ($notify_send_id, $unit_id){
        $query = self::query();
        $query->where('unit_id', '=', $unit_id);
        $query->where('notify_send_id', '=', $notify_send_id);
        return $query->exists();
    }
    public static function checkObjectTitle ($notify_send_id, $title_id){
        $query = self::query();
        $query->where('title_id', '=', $title_id);
        $query->where('notify_send_id', '=', $notify_send_id);
        return $query->exists();
    }
}
