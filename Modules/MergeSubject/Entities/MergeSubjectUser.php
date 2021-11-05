<?php

namespace Modules\MergeSubject\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\MergeSubject\Entities\MergeSubjectUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubjectUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubjectUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubjectUser query()
 * @mixin \Eloquent
 */
class MergeSubjectUser extends Model
{
    protected $table = 'el_merge_subject_user';
    protected $fillable = [
        'user_id',
        'merge_subject_id',
        'type',
        'processed',
    ];
}
