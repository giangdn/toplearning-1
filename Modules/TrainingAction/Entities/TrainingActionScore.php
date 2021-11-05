<?php

namespace Modules\TrainingAction\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingAction\Entities\TrainingActionScore
 *
 * @property int $id
 * @property int $training_action_id
 * @property int $type
 * @property int $from
 * @property int $to
 * @property int $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore whereTrainingActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\TrainingAction\Entities\TrainingActionScore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingActionScore extends Model
{
    protected $table = 'el_training_action_scores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'training_action_id',
        'from',
        'to',
        'score'
    ];
}
