<?php

namespace App;

use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Response;

/**
 * Modules\Libraries\Entities\EmulationProgramCondition
 *
 * @property int $id
 * @property int $emulation_id
 * @property int|null $course_id
 * @property int $type
 * @property int|null $quiz_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramCondition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramCondition whereEmulationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramCondition whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramCondition whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\EmulationProgramCondition whereType($value)
 * @mixin \Eloquent
 */
class EmulationProgramCondition extends Model
{
    protected $table = 'el_emulation_program_condition';
    protected $fillable = [
        'emulation_id',
        'course_id',
        'quiz_id',
        'type',
    ];
    protected $primaryKey = 'id';

    public static function checkConditionCourse ($emulation_id, $course_id, $type){
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        $query->where('emulation_id', '=', $emulation_id);
        $query->where('type', '=', $type);
        return $query->exists();
    }
    public static function checkConditionQuiz ($emulation_id, $quiz_id, $type){
        $query = self::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('emulation_id', '=', $emulation_id);
        $query->where('type', '=', $type);
        return $query->exists();
    }

    public static function getType($type, $emulation_id){
        $profile = Profile::leftJoin('el_titles AS b', 'b.code', '=', 'title_code')
            ->leftJoin('el_unit AS c', 'c.code', '=', 'unit_code')
            ->where('type', '=', $type)
            ->first(['type', 'c.id as course_id', 'b.id as quiz_id']);

        $status = LibrariesObject::where('type', '=', $profile->type)
            ->orWhere('quiz_id', '=', $profile->quiz_id)
            ->orWhere('course_id', '=', $profile->course_id)
            ->where('emulation_id', '=', $emulation_id)
            ->first();
        return $status;
    }
}
