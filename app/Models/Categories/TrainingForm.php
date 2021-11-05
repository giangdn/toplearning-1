<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Categories\TrainingForm
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingForm query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingForm whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingForm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingForm whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingForm extends BaseModel
{
    protected $table = 'el_training_form';
    protected $fillable = [
        'code',
        'name',
        'training_type_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã loại hình đào tạo',
            'name' => 'Tên loại hình đào tạo',
        ];
    }
}
