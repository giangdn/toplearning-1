<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Categories\UnitType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UnitType extends BaseModel
{
    protected $table = 'el_unit_type';
    protected $fillable = [
        'name',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên đơn vị',
        ];
    }
}
