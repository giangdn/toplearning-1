<?php

namespace Modules\Permission\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Permission\Entities\UnitManagerSetting
 *
 * @property int $id
 * @property int $unit_id
 * @property string|null $priority1 Ưu tiên 1
 * @property string|null $priority2 Ưu tiên 2
 * @property string|null $priority3 Ưu tiên 3
 * @property string|null $priority4 Ưu tiên 4
 * @property int|null $unit_by
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting wherePriority1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting wherePriority2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting wherePriority3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting wherePriority4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManagerSetting whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class UnitManagerSetting extends BaseModel
{
    protected $table ='el_unit_manager_setting';
    protected $fillable = [
        'unit_id',
        'priority1',
        'priority2',
        'priority3',
        'priority4',
        'unit_by',
    ];
}
