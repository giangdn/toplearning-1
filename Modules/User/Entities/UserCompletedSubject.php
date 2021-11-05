<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\User\Entities\UserCompletedSubject
 *
 * @property int $id
 * @property int $user_id
 * @property int $subject_id
 * @property int $course_id
 * @property string|null $date_completed ngày hoàn thành
 * @property string|null $process_type E: elearning, O: offline, G: gộp chuyên đề, T: tách chuyên đề, D: duyệt hoàn thành
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject whereDateCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject whereProcessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCompletedSubject whereUserId($value)
 * @mixin \Eloquent
 */
class UserCompletedSubject extends Model
{
    protected $table = 'el_user_completed_subject';
    protected $fillable = [
        'user_id',
        'subject_id',
        'course_id',
        'course_type',
        'date_completed',
        'process_type',
    ];
}
