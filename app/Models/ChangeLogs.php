<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChangeLogs
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $model
 * @property string|null $data
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs whereUserId($value)
 * @property int $model_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs whereModelId($value)
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeLogs whereType($value)
 */
class ChangeLogs extends Model
{
    protected $table = 'el_change_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['model'];
}
