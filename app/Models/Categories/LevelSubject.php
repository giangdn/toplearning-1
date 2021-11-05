<?php

namespace App\Models\Categories;

use App\BaseModel;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Online\Entities\OnlineCourseComplete;

/**
 * App\Models\Categories\Subject
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $training_program_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $condition
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Subject whereCondition($value)
 * @property string|null $description
 * @property string|null $content
 * @method static Builder|Subject whereContent($value)
 * @method static Builder|Subject whereDescription($value)
 */
class LevelSubject extends BaseModel
{
    protected $table = 'el_level_subject';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'status',
    ];

    public static function getAttributeName() {
        return [
            'code'=>'Mã',
            'name' => 'Mãng nghiệp vụ',
            'status' => 'Trạng thái',
        ];
    }

    public static function getLevelSubjectByTrainingProgram($training_program_id) {
        $query = self::query();
        return $query->get();
    }
}
