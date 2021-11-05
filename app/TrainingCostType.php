<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TrainingCostType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingCostType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingCostType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingCostType query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingCostType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingCostType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingCostType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingCostType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingCostType extends Model
{
    protected $table ='el_training_cost_type';
    protected $primaryKey ='id';
    protected $fillable=[
      'id',
      'name'
    ];
}
