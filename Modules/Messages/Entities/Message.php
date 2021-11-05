<?php

namespace Modules\Messages\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;


/**
 * Modules\Messages\Entities\Message
 *
 * @property int $id
 * @property string|null $room
 * @property string $message
 * @property int $from
 * @property int $to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    protected $table='messages';
    protected $fillable = ['room', 'message', 'from', 'to'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
