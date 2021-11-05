<?php

namespace Modules\TrainingAction\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingAction\Entities\TrainingActionTeacher
 *
 * @property int $id
 * @property int $training_action_id
 * @property int $user_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionTeacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionTeacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionTeacher query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionTeacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionTeacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionTeacher whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionTeacher whereTrainingActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionTeacher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionTeacher whereUserId($value)
 * @mixin \Eloquent
 */
class TrainingActionTeacher extends Model
{
    protected $table = 'el_training_action_teachers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'training_action_id',
        'user_id',
        'status',
    ];
}
