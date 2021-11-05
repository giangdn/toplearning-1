<?php

namespace Modules\TrainingPlan\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class TrainingPlanDetailTypeCost extends BaseModel
{
    protected $table = 'el_training_plan_detail_type_cost';
    protected $fillable = [
        'training_plan_detail_id',
        'status',
        'cost_id',
        'training_plan_id',
    ];
    protected $primaryKey = 'id';
}
