<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\TrainingType
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class TrainingType extends Model
{
    protected $table="el_training_type";
	
	 protected $fillable = [
        'code',
        'name',    
        'status'
    ];
	
	protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã chức vụ',
            'name' => 'Tên chức vụ',
            'status' => 'Trạng thái',
        ];
    }
	
}
