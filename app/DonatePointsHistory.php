<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DonatePointsHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePointsHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePointsHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePointsHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePointsHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePointsHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePointsHistory whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePointsHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePointsHistory whereUserId($value)
 * @mixin \Eloquent
 */
class DonatePointsHistory extends Model
{
    protected $table = 'el_donate_points_history';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'score',
    ];
}
