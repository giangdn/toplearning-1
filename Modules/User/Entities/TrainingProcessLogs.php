<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\User\Entities\TrainingProcessLogs
 *
 * @property int $id
 * @property int $process_id
 * @property string|null $module
 * @property string|null $action
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs whereProcessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $type 1: gộp, 2: tách, 3: hoàn thành quá trình đào tạo
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcessLogs whereType($value)
 */
class TrainingProcessLogs extends Model
{
    protected $table ='el_training_process_logs';
    protected $fillable = [
        'process_id',
        'module',
        'action',
        'type'
    ];

    public static function saveLogs($process_id,$module,$action,$type)
    {
        $user_id = \Auth::id();
        $model = new TrainingProcessLogs();
        $model->process_id = $process_id;
        $model->module = $module;
        $model->action = $action;
        $model->created_by = $user_id;
        $model->type = $type;
        $model->save();
    }
}
