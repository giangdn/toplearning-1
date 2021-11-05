<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizNoteByUserSecond
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizNoteByUserSecond whereUserId($value)
 * @mixin \Eloquent
 */
class QuizNoteByUserSecond extends Model
{
    protected $table = 'el_quiz_note_by_user_second';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quiz_id',
        'user_id',
        'title',
        'content',
    ];
}
