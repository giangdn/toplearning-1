<?php

namespace Modules\MergeSubject\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\MergeSubject\Entities\MergeSubject
 *
 * @property int $id
 * @property string $subject_old
 * @property int $subject_new
 * @property int|null $subject_old_complete
 * @property int $type
 * @property int $status
 * @property int|null $approved_by
 * @property int|null $approved_date
 * @property int|null $number_merge_completed
 * @property int|null $number_merge_subject
 * @property string|null $note
 * @property int $merge_option
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereApprovedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereMergeOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereNumberMergeCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereNumberMergeSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereSubjectNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereSubjectOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereSubjectOldComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubject whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class MergeSubject extends BaseModel
{
    protected $table = 'el_merge_subject';
    protected $fillable = [];
}
