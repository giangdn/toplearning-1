<?php

namespace Modules\SubjectRegister\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\SubjectRegister\Entities\SubjectRegister
 *
 * @property int $id
 * @property int $user_id
 * @property int $subject_id
 * @property int $status 1: Đã duyệt, 2: Chưa duyệt, 0: Từ chối
 * @property int|null $note
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectRegister whereUserId($value)
 * @mixin \Eloquent
 */
class SubjectRegister extends Model
{
    protected $table= 'el_subject_register';
    protected $fillable = [
        'user_id',
        'user_type',
        'subject_id',
        'status_id',
        'note',
        'created_by',
        'updated_by',
        'unit_by',
        'created_at',
        'updated_at',
    ];
}
