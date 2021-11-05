<?php

namespace App;

use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Response;

/**
 * Modules\Libraries\Entities\EmulationUserGetArmorial
 *
 * @property int $id
 * @property int $emulation_id
 * @property int|null $type
 * @property int|null $point
 * @property int|null $user_id
 * @property int|null $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial whereLibrariesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationUserGetArmorial whereUserId($value)
 * @mixin \Eloquent
 */
class EmulationUserGetArmorial extends Model
{
    protected $table = 'el_emulation_user_get_armorial';
    protected $fillable = [
        'user_id',
        'emulation_id',
        'armorial_id',
    ];
    protected $primaryKey = 'id';

    public static function createUserGetArmorial($emulation_id, $armorial_id, $user_id) {
        $model = self::firstOrNew(['user_id' => $user_id, 'emulation_id' => $emulation_id]);
        $model->user_id = $user_id;
        $model->emulation_id = $emulation_id;
        $model->armorial_id = $armorial_id;
        $model->save();
    }

    public static function deleteUserGetArmorial($emulation_id, $armorial_id, $user_id) {
        $model = self::where('emulation_id',$emulation_id)
        ->where('armorial_id',$armorial_id)
        ->where('user_id',$user_id)
        ->first();
        if ($model) {
            $model->delete();
        }
    }
}
