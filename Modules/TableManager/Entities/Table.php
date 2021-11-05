<?php

namespace Modules\TableManager\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TableManager\Entities\Table
 *
 * @property int $id
 * @property string $code
 * @property string $name tên table
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Table newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Table newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Table query()
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Table extends Model
{
    protected $table = 'el_table';
    protected $fillable = [
        'code',
        'name',
    ];
}
