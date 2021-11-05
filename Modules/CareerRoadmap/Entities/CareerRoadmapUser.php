<?php

namespace Modules\CareerRoadmap\Entities;

use App\BaseModel;
use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;

/**
 * Modules\CareerRoadmap\Entities\CareerRoadmapUser
 *
 * @property int $id
 * @property string $name
 * @property int $primary
 * @property int $title_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapUser wherePrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapUser whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmapUser whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\CareerRoadmap\Entities\CareerRoadmapTitleUser[] $titles
 * @property-read int|null $titles_count
 */
class CareerRoadmapUser extends BaseModel
{
    protected $table = 'career_roadmap_user';
    protected $fillable = [
        'user_id',
        'name'
    ];

    public function titles() {
        return $this->hasMany('Modules\CareerRoadmap\Entities\CareerRoadmapTitleUser', 'career_roadmap_user_id', 'id');
    }

    public function getTitles($level = null, $title_id = null) {
        $query = $this->titles()->with('title');
        if ($title_id){
            $query->where('title_id', '=', $title_id);
        }
        if (isset($level)){
            $query->where('level', '=', $level);
        }
        $query->orderBy('level', 'ASC');
        return $query->get();
    }

    public static function getCourseCompleteByTitle($title_id)
    {
        $query = TrainingRoadmap::query()
            ->select([
                'b.name as course_name',
            ])
            ->from('el_trainingroadmap AS a')
            ->leftJoin('el_course_view as b',function ($join){
                $join->on('b.subject_id', '=', 'a.subject_id');
                $join->on('b.course_type','=','a.training_form');
            })
            ->whereExists(function ($subquery) {
                $subquery->select(['id'])
                    ->from('el_course_complete')
                    ->whereColumn('course_id', '=', 'b.course_id')
                    ->where('user_id', '=', \Auth::id())
                    ->whereColumn('course_type', '=', 'b.course_type');
            })
            ->where('a.title_id', '=', $title_id);

        return $query->get();
    }

    public static function getCourseUnCompleteByTitle($title_id)
    {
        $query = TrainingRoadmap::query()
            ->select([
                'b.name as course_name',
            ])
            ->from('el_trainingroadmap AS a')
            ->leftJoin('el_course_view as b',function ($join){
                $join->on('b.subject_id', '=', 'a.subject_id');
                $join->on('b.course_type','=','a.training_form');
            })
            ->whereNotExists(function ($subquery) {
                $subquery->select(['id'])
                    ->from('el_course_complete')
                    ->whereColumn('course_id', '=', 'b.course_id')
                    ->where('user_id', '=', \Auth::id())
                    ->whereColumn('course_type', '=', 'b.course_type');
            })
            ->where('a.title_id', '=', $title_id);

        return $query->get();
    }
}
