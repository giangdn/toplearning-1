<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Categories\TrainingLocation
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $district_id
 * @property int|null $province_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingLocation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingLocation extends BaseModel
{
    protected $table = 'el_training_location';
    protected $fillable = [
        'code',
        'name',
        'status',
        'province_id',
        'district_id'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã địa điểm đào tạo',
            'name' => 'Tên địa điểm đào tạo',
            'status' => 'Trạng thái',
            'province_id' => 'Tỉnh thành',
            'district_id' => 'Quận huyện',
        ];
    }
}
