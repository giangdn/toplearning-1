<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\Position
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position query()
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Position extends BaseModel
{
   protected $table="el_position";

   protected $fillable = [
        'code',
        'name',
        'status'
    ];

	protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã chức vụ',
            'name' => 'Tên chức vụ',
            'status' => 'Trạng thái',
        ];
    }


}
