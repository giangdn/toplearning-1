<?php

namespace Modules\TrainingAction\Entities;

use App\Traits\ChangeLogs;
use App\Traits\MultiLang;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingAction\Entities\TrainingActionCategory
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingActionCategory extends Model
{
    use ChangeLogs, MultiLang;
    
    protected $table = 'el_training_action';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'name_en',
        'status'
    ];
}
