<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\HasChange
 *
 * @property int $id
 * @property string $table_name tên table
 * @property int $record_id id table
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|HasChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HasChange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HasChange query()
 * @method static \Illuminate\Database\Eloquent\Builder|HasChange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HasChange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HasChange whereRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HasChange whereTableName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HasChange whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HasChange extends Model
{
    protected $table='el_has_change';
    protected $fillable =[
      'id',
      'table_name',
      'record_id',
      'type',
    ];
}
