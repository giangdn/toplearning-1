<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\Discipline
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline query()
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Discipline extends BaseModel
{
    protected $table="el_discipline";
	protected $fillable = [
        'code',
        'name',
        'status'
    ];

	protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã vi phạm',
            'name' => 'Tên vi phạm',
            'status' => 'Trạng thái',
        ];
    }
}
