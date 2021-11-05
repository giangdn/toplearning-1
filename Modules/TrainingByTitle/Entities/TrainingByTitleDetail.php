<?php

namespace Modules\TrainingByTitle\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingByTitle\Entities\TrainingByTitleDetail
 *
 * @property int $id
 * @property int $training_title_id
 * @property int $title_id
 * @property int $training_title_category_id
 * @property int $subject_id
 * @property string $subject_code
 * @property string $subject_name
 * @property int $num_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Categories\Titles|null $title
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereNumDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereSubjectCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereTrainingTitleCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereTrainingTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingByTitleDetail extends Model
{
    protected $table = 'el_training_by_title_detail';
    protected $primaryKey = 'id';
    protected $fillable = [
        'training_title_id',
        'title_id',
        'training_title_category_id',
        'subject_id',
        'subject_code',
        'subject_name',
        'num_date',
        'num_time',
    ];

    public function title() {
        return $this->hasOne('App\Models\Categories\Titles', 'id', 'title_id');
    }
}
