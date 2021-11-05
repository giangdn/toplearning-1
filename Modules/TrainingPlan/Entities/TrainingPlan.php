<?php

namespace Modules\TrainingPlan\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingPlan\Entities\TrainingPlan
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $year
 * @property int $status
 * @property int $unit_id
 * @property string|null $attachment
 * @property string|null $type_costs Tất cả loại chi phí
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereTypeCosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlan whereYear($value)
 * @mixin \Eloquent
 */
class TrainingPlan extends BaseModel
{
    protected $table = 'el_training_plan';
    protected $fillable = [
        'code',
        'name',
        'year',
        'unit_id',
        'status',
        'type_costs',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã Kế hoạch',
            'name' => 'Tên kế hoạch',
            'status' => 'Trạng thái',
            'year' => 'Năm',
            'unit_id' => 'Đơn vị',
            'type_costs' => 'Loại chi phí',
        ];
    }
}
