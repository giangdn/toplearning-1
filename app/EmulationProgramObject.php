<?php

namespace App;

use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Response;

/**
 * Modules\Libraries\Entities\EmulationProgramObject
 *
 * @property int $id
 * @property int $emulation_id
 * @property int|null $title_code
 * @property int|null $unit_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject whereLibrariesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramObject whereUserId($value)
 * @mixin \Eloquent
 */
class EmulationProgramObject extends Model
{
    protected $table = 'el_emulation_program_object';
    protected $fillable = [
        'emulation_id',
        'unit_id',
        'title_code',
        'user_id',
    ];
    protected $primaryKey = 'id';

    public static function checkObjectUnit($emulation_id, $unit_id) {
        $query = self::query();
        $query->where('unit_id', '=', $unit_id);
        $query->where('emulation_id', '=', $emulation_id);
        return $query->exists();
    }
    public static function checkObjectTitle($emulation_id, $title_code) {
        $query = self::query();
        $query->where('title_code', '=', $title_code);
        $query->where('emulation_id', '=', $emulation_id);
        return $query->exists();
    }
}
