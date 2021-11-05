<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\District
 *
 * @property int $id
 * @property string $name
 * @property int $province_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\District newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\District newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\District query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\District whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\District whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\District whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\District whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\District whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class District extends BaseModel
{
    protected $table = 'el_district';
    protected $primaryKey = 'id';
    protected $fillable = ['id','name','province_id'];

    public static function getAttributeName() {
        return [
            'id'    =>'Mã quận huyện',
            'name' => 'Tên quận huyện',
            'province_id' => 'Mã tỉnh thành'
        ];
    }
}
