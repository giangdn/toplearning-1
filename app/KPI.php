<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\KPI
 *
 * @property int $id
 * @property string $user_code
 * @property int $year
 * @property string|null $quarter_1
 * @property string|null $quarter_2
 * @property string|null $quarter_3
 * @property string|null $quarter_4
 * @property string|null $quarter_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereQuarter1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereQuarter2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereQuarter3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereQuarter4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereQuarterYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereUserCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KPI whereYear($value)
 * @mixin \Eloquent
 */
class KPI extends Model
{
    protected $table = 'el_kpi';
    protected $fillable = [
        'user_code',
        'year',
        'quarter_1',
        'quarter_2',
        'quarter_3',
        'quarter_4',
        'quarter_year',
    ];
    protected $primaryKey = 'id';

    public static function getKpi($user_code, $year = null) {
        $query = self::query();
        $query->where('user_code', '=', $user_code);
        if ($year) {
            $query->where('year', '=', $year);
        }

        if ($query->exists()) {
            return $query->first();
        }

        return null;
    }
}