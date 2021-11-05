<?php

namespace Modules\TrainingAction\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingAction\Entities\TrainingActionPersonChargeRole
 *
 * @property int $id
 * @property int $person_charge_id
 * @property int $role_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonChargeRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonChargeRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonChargeRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonChargeRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonChargeRole wherePersonChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionPersonChargeRole whereRoleId($value)
 * @mixin \Eloquent
 */
class TrainingActionPersonChargeRole extends Model
{
    public $timestamps = false;
    protected $table = 'el_person_charge_roles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'role_id',
        'person_charge_id'
    ];
}
