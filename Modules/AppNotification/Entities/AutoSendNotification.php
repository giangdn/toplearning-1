<?php

namespace Modules\AppNotification\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\AppNotification\Entities\AutoSendNotification
 *
 * @property int $id
 * @property string $user_ids
 * @property string $message
 * @property string|null $url
 * @property string|null $image
 * @property string|null $error
 * @property int $status 2: Chưa gửi, 3: đang gửi, 1: đã gửi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereUserIds($value)
 * @mixin \Eloquent
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|AutoSendNotification whereTitle($value)
 */
class AutoSendNotification extends Model
{
    protected $table = 'el_auto_send_notifications';
    protected $fillable = [
        'user_ids',
        'title',
        'message',
        'url',
        'image',
        'error',
        'status',
    ];
    
    public function getUserDeviceTokens() {
        $user_ids = explode(',', $this->user_ids);
        if (empty($user_ids)) {
            return false;
        }
    
        return AppDeviceToken::whereIn('user_id', $user_ids)
            ->pluck('device_token')
            ->toArray();
    }
}
