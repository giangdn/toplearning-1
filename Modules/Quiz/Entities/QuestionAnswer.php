<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuestionAnswer
 *
 * @property int $id
 * @property int $question_id
 * @property string $title
 * @property int $is_text
 * @property int $correct_answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereIsText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float|null $percent_answer
 * @property string|null $feedback_answer
 * @property string|null $matching_answer
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereFeedbackAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereMatchingAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer wherePercentAnswer($value)
 */
class QuestionAnswer extends Model
{
    protected $table = 'el_question_answer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'question_id',
        'correct_answer',
        'feedback_answer',
        'matching_answer',
        'percent_answer',
        'image_answer',
        'fill_in_correct_answer',
    ];

    public static function getAttributeName() {
        return [
            'title' => 'Tên câu trả lời',
            'question_id' => 'Câu hỏi',
            'correct_answer' => 'Đáp án đúng',
        ];
    }
}
