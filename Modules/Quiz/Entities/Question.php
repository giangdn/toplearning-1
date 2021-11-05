<?php

namespace Modules\Quiz\Entities;

use App\BaseModel;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\Question
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int|null $category_id
 * @property int $multiple
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property string|null $note
 * @property string|null $feedback
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Question whereNote($value)
 */
class Question extends BaseModel
{
    use ChangeLogs;
    protected $table = 'el_question';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type',
        'category_id',
        'multiple',
        'created_by',
        'updated_by',
        'status',
        'note',
        'feedback',
        'shuffle_answers',
        'answer_horizontal',
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên câu hỏi',
            'type' => 'Loại',
            'category_id' => 'Danh mục',
            'multiple' => 'Chọn nhiều',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor'),
        ];
    }
}
