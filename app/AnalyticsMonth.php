<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AnalyticsMonth
 *
 * @property int $id
 * @property int $user_id
 * @property string $month
 * @property int $access
 * @property float $minute
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth whereAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth whereMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnalyticsMonth whereUserId($value)
 * @mixin \Eloquent
 */
class AnalyticsMonth extends Model
{
    protected $table = 'el_analytics_month';
    protected $primaryKey = 'id';
    protected $fillable = [
        'access',
        'minute'
    ];
}
