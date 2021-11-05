<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\TrainingObject
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingObject whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class TrainingObject extends BaseModel
{
    protected $table="el_training_object";
	protected $fillable = [
        'code',
        'name',
        'status'
    ];

	protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã đối tượng đào tạo',
            'name' => 'Tên đối tượng đào tạo',
            'status' => 'Trạng thái',
        ];
    }

}
