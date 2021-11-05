<?php

namespace App;

use Carbon\Carbon;
use http\Client\Request;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

/**
 * App\Visits
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $datetime
 * @property string|null $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Visits newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visits newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visits query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereContent($value)
 */
class Note extends Model
{
    protected $table = 'el_note';
    protected $fillable = [
        'date_time',
        'content',
        'user_id',
        'type',
    ];
}
