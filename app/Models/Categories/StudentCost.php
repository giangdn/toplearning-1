<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Categories\StudentCost
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\StudentCost whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class StudentCost extends BaseModel
{
    protected $table = 'el_student_cost';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'status'
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên chi phí học viên',
            'status' => 'Trạng thái'
        ];
    }
}
