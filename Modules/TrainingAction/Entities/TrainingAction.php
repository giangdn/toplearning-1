<?php

namespace Modules\TrainingAction\Entities;

use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingAction\Entities\TrainingAction
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingAction extends Model
{
    use ChangeLogs;
    
    protected $table = 'el_training_action';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'name_en',
        'status',
    ];
}
