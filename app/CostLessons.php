<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\CostLessons
 *
 * @property int $id
 * @property string $name
 * @property int $cost
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostLessons newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostLessons newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostLessons query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostLessons whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostLessons whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostLessons whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostLessons whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostLessons whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostLessons whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostLessons extends Model
{
    protected $table = 'el_cost_lessons';
    protected $fillable = [
        'name',
        'cost',
        'status'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'cost' => 'Chi phí tiết giảng',
            'name' => 'Tên chi phí tiết giảng',
            'status' => 'Trạng thái'
        ];
    }
}
