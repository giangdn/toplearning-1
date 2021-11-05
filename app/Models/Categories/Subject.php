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
 * @property int $level_subject_id
 * @property string|null $created_date ngày khởi tạo
 * @property int|null $created_by
 * @property int|null $unit_id
 * @method static Builder|Subject whereCreatedBy($value)
 * @method static Builder|Subject whereCreatedDate($value)
 * @method static Builder|Subject whereLevelSubjectId($value)
 * @method static Builder|Subject whereUnitId($value)
 */
class Subject extends BaseModel
{
    use ChangeLogs;

    protected $table = 'el_subject';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'level_subject_id',
        'training_program_id',
        'created_date',
        'created_by',
        'unit_id',
        'condition',
        'status',
        'description',
        'content',
    ];

    public function isCompleted($user_id = null) {
        if (empty($user_id)) {
            $user_id = \Auth::id();
        }

        $query = OnlineCourseComplete::query();
        $query->where('user_id', '=', $user_id)
            ->whereIn('course_id', function ($builder) {
                $builder->select(['id'])
                    ->from('el_online_course')
                    ->where('subject_id', '=', $this->id);
            });

        if ($query->exists()) {
            return true;
        }

        $query = OfflineCourseComplete::query();
        $query->where('user_id', '=', $user_id)
            ->whereIn('course_id', function ($builder) {
                $builder->select(['id'])
                    ->from('el_offline_course')
                    ->where('subject_id', '=', $this->id);
            });

        if ($query->exists()) {
            return true;
        }

        return false;
    }

    public static function getAttributeName() {
        return [
            'code' => trans('backend.subject_code'),
            'name' => trans('backend.subject_name'),
            'level_subject_id' =>  trans('backend.levels_subject'),
            'training_program_id' => trans('backend.training_program_name'),
        ];
    }

    public static function getSubjectByTrainingProgram($training_program_id) {
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('training_program_id', '=', $training_program_id);
        return $query->get();
    }
    public function scopeActive($query) {
        return $query->where('status', '=', 1);
    }
}
