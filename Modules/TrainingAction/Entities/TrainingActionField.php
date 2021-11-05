<?php

namespace Modules\TrainingAction\Entities;

use App\Traits\ChangeLogs;
use App\Traits\MultiLang;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingAction\Entities\TrainingActionField
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingActionField extends Model
{
    use ChangeLogs, MultiLang;
    
    protected $table = 'el_training_action_fields';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'name_en',
        'status'
    ];
}
