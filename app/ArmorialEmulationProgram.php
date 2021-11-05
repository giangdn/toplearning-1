<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EmulationProgram
 *
 * @property int $id
 * @property string|null $imgae
 * @property string $code
 * @property string $name
 * @property string $time_start
 * @property string $time_end
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereTimeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereDescription($value)
 * @mixin \Eloquent
 */
class ArmorialEmulationProgram extends Model
{
    protected $table = 'el_armorial_emulation_program';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'name',
        'description',
        'min_score',
        'max_score',
    ];

    public static function getAttributeName() {
        return [
            'name_armorials' => 'Tên huy hiệu',
            'min_score' => 'Điểm thấp nhất',
            'max_score' => 'Điểm cao nhất',
            'description_armorials' => 'Mô tả',
        ];
    }
}
