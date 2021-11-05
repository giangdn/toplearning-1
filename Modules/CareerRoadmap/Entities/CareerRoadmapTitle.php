<?php

namespace Modules\CareerRoadmap\Entities;

use App\Models\Categories\Subject;
use Illuminate\Database\Eloquent\Model;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;

/**
 * Modules\CareerRoadmap\Entities\CareerRoadmapTitle
 *
 * @property int $id
 * @property int $career_roadmap_id
 * @property int $title_id
 * @property int|null $parent_id
 * @property int $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Categories\Titles|null $title
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle query()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle whereCareerRoadmapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CareerRoadmapTitle extends Model
{
    protected $table = 'career_roadmap_titles';
    protected $fillable = [
        'title_id',
        'level',
        'parent_id',
        'seniority',
    ];

    public function title() {
        return $this->hasOne('App\Models\Categories\Titles', 'id', 'title_id');
    }

    public static function getSubjectRoadmap($title_id){
        $query = TrainingRoadmap::query();
        $query->with('subject');
        $query->select([
            'a.id',
            'a.subject_id',
            'b.code AS subject_code',
            'b.name AS subject_name',
            'c.name AS title_name',
        ]);
        $query->from('el_trainingroadmap AS a');
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
        $query->leftJoin('el_titles AS c', 'c.id', '=', 'a.title_id');
        $query->where('a.title_id', '=', $title_id);

        $rows = $query->get();
        foreach ($rows as $row) {
            $subject = Subject::find($row->subject_id);
            $row->result = $subject ? ($subject->isCompleted() ? 1 : 0) : 0;
        }
        return $rows;
    }
}
