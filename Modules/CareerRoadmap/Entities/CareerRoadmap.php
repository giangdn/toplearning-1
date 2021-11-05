<?php

namespace Modules\CareerRoadmap\Entities;

use App\BaseModel;
use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;

/**
 * Modules\CareerRoadmap\Entities\CareerRoadmap
 *
 * @property int $id
 * @property string $name
 * @property int $primary
 * @property int $title_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmap query()
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmap whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmap wherePrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmap whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CareerRoadmap whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\CareerRoadmap\Entities\CareerRoadmapTitle[] $titles
 * @property-read int|null $titles_count
 */
class CareerRoadmap extends BaseModel
{
    protected $table = 'career_roadmap';
    protected $fillable = [
        'name'
    ];

    public function titles() {
        return $this->hasMany('Modules\CareerRoadmap\Entities\CareerRoadmapTitle', 'career_roadmap_id', 'id');
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
                'b.code as course_code',
            ])
            ->from('el_trainingroadmap AS a')
            ->join('el_course_view as b',function ($join){
                $join->on('b.subject_id', '=', 'a.subject_id');
                $join->on('b.course_type', 'LIKE', DB::raw( "CONCAT('%', mdl_a.training_form, '%')"));
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
                'b.code as course_code',
            ])
            ->from('el_trainingroadmap AS a')
            ->join('el_course_view as b',function ($join){
                $join->on('b.subject_id', '=', 'a.subject_id');
                $join->on('b.course_type', 'LIKE', DB::raw( "CONCAT('%', mdl_a.training_form, '%')"));
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
