<?php

namespace Modules\TrainingRoadmap\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingRoadmap\Entities\TrainingRoadmapFinish
 *
 * @property int $id
 * @property int|null $title_id
 * @property int|null $level_subject_id mãng nghiệp vụ
 * @property string|null $level_subject_name mãng nghiệp vụ
 * @property int|null $user_finish số người hoàn thành
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish whereLevelSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish whereLevelSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmapFinish whereUserFinish($value)
 * @mixin \Eloquent
 */
class TrainingRoadmapFinish extends Model
{
    protected $table = "el_training_roadmap_finish";
    protected $fillable = [
        'title_id',
        'level_subject_id',
        'level_subject_name',
        'user_finish',
    ];

}
