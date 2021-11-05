<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\User\Entities\TrainingProcess
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_type
 * @property int|null $course_id
 * @property string|null $course_code
 * @property string|null $course_name
 * @property int|null $course_type
 * @property int $subject_id
 * @property string $subject_code
 * @property string $subject_name
 * @property string|null $titles_code
 * @property string|null $titles_name
 * @property string|null $unit_code
 * @property string|null $unit_name
 * @property string|null $start_date
 * @property string|null $end_date
 * @property float|null $mark
 * @property int|null $pass
 * @property int|null $certificate
 * @property string|null $time_complete
 * @property int|null $status
 * @property int|null $process_type
 * @property int|null $merge_subject_id
 * @property int|null $move_id
 * @property string|null $note
 * @property int|null $approved_by
 * @property string|null $approved_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereApprovedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereCourseCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereCourseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereMergeSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereMoveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess wherePass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereProcessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereSubjectCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereTimeComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereTitlesCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereTitlesName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProcess whereUserType($value)
 * @mixin \Eloquent
 */
class TrainingProcess extends Model
{
    protected $table ='el_training_process';
    protected $fillable = [
        'user_id',
        'user_type',
        'course_id',
        'course_code',
        'course_name',
        'course_type',
        'subject_id',
        'subject_code',
        'subject_name',
        'titles_code',
        'titles_name',
        'unit_code',
        'unit_name',
        'start_date',
        'end_date',
        'mark',
        'pass',
        'certificate',
        'status',
        'process_type',
        'note',
        'approved_by',
        'approved_date',
        'move_id',
        'time_complete',
        'course_old'
    ];
}
