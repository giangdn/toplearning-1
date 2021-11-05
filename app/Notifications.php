<?php

namespace App;

use Illuminate\Notifications\DatabaseNotification;

/**
 * App\Notifications
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notifications whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] get($columns = ['*'])
 */
class Notifications extends DatabaseNotification
{
    protected $table = 'el_notifications';
    
    public function getMessages() {
    
    }
}
