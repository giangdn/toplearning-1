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
 * @property int|null $type
 * @property int|null $point
 * @property int|null $user_id
 * @property int|null $course_id
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
class EmulationPromotion extends Model
{
    protected $table = 'el_emulation_promotion';
    protected $fillable = [
        'user_id',
        'point',
        'type',
        'course_id',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';
}
