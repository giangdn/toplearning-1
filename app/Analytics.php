<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Analytics
 *
 * @property int $id
 * @property int $user_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $ip_address
 * @property string $day
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analytics whereUserId($value)
 * @mixin \Eloquent
 */
class Analytics extends Model
{
    protected $table = 'el_analytics';
    protected $primaryKey = 'id';
}
