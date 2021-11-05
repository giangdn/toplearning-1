<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\AbsentReason
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason query()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class AbsentReason extends BaseModel
{
    protected $table="el_absent_reason";
	protected $fillable = [
        'code',
        'name',
        'status'
    ];

	protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã lý do vắng mặt',
            'name' => 'Tên lý do vắng mặt',
            'status' => 'Trạng thái',
        ];
    }
}
