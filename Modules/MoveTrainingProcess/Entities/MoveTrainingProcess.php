<?php

namespace Modules\MoveTrainingProcess\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\MoveTrainingProcess\Entities\MoveTrainingProcess
 *
 * @property int $id
 * @property int $employee_old
 * @property int $employee_new
 * @property string|null $move_process_id
 * @property int|null $status null: Chưa duyệt, 1: Đã duyệt, 0: từ chối
 * @property string|null $approved_date
 * @property int|null $approved_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess query()
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereApprovedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereEmployeeNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereEmployeeOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereMoveProcessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoveTrainingProcess whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class MoveTrainingProcess extends BaseModel
{
    protected $table = 'el_move_training_process';
    protected $fillable = [
        'employee_old',
        'employee_new',
        'move_process_id',
        'status',
        'approved_date',
        'approved_by',
        'unit_by',
    ];
}
