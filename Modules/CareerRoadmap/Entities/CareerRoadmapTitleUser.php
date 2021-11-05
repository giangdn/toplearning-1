<?php

namespace Modules\CareerRoadmap\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;

/**
 * Modules\CareerRoadmap\Entities\CareerRoadmapTitleUser
 *
 * @property int $id
 * @property int $career_roadmap_user_id
 * @property int $title_id
 * @property int|null $parent_id
 * @property int $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Categories\Titles|null $title
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser whereCareerRoadmapUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapTitleUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CareerRoadmapTitleUser extends Model
{
    protected $table = 'career_roadmap_titles_user';
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
            'b.code AS subject_code',
            'b.name AS subject_name',
            'c.name AS title_name',
        ]);
        $query->from('el_trainingroadmap AS a');
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
        $query->leftJoin('el_titles AS c', 'c.id', '=', 'a.title_id');
        $query->where('a.title_id', '=', $title_id);

        return $query->get();
    }
}
