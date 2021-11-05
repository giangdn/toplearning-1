<?php

namespace Modules\TrainingAction\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingAction\Entities\TrainingActionPersonCharge
 *
 * @property int $id
 * @property int $user_id
 * @property int $field_id
 * @property int $max_support
 * @property int $type
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge whereFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge whereMaxSupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonCharge whereUserId($value)
 * @mixin \Eloquent
 */
class TrainingActionPersonCharge extends Model
{
    protected $table = 'el_person_charge';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'field_id',
        'role_id',
        'max_support',
        'type',
        'status'
    ];
}