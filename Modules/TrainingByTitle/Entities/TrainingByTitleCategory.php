<?php

namespace Modules\TrainingByTitle\Entities;

use App\CourseView;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Subject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\User\Entities\UserCompletedSubject;

/**
 * Modules\TrainingByTitle\Entities\TrainingByTitleCategory
 *
 * @property int $id
 * @property int $training_title_id
 * @property int $title_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\TrainingByTitle\Entities\TrainingByTitleDetail[] $trainingtitledetail
 * @property-read int|null $trainingtitledetail_count
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleCategory whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleCategory whereTrainingTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitleCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingByTitleCategory extends Model
{
    protected $table = 'el_training_by_title_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'training_title_id',
        'title_id',
        'name',
    ];

    public function trainingtitledetail() {
        return $this->hasMany('Modules\TrainingByTitle\Entities\TrainingByTitleDetail', 'training_title_category_id', 'id');
    }
    public function getChildTrainingByTitleCategory($start_date){
        $childs = TrainingByTitleDetail::where('training_title_category_id', '=', $this->id)->get();

        $level_subject_arr = [];
        foreach ($childs as $child){
            $subject = Subject::find($child->subject_id);
            $level_subject = LevelSubject::find($subject->level_subject_id);
            $level_subject_arr[$level_subject->id] = @$level_subject->name;
            $end_date = Carbon::parse($start_date)->addDays($child->num_date)->format('d/m/Y');

            $child->level_subject = @$level_subject->id;
            $child->start_date = get_date($start_date);
            $child->end_date = $end_date;

            $count_course_by_subject = CourseView::whereSubjectId($child->subject_id)->whereStatus(1)->count();
            $count_course_completed_by_subject = UserCompletedSubject::whereSubjectId($child->subject_id)->whereUserId(Auth::id())->count();

            $child->percent_subject = ($count_course_completed_by_subject/($count_course_by_subject > 0 ? $count_course_by_subject : 1)) * 100;
            $child->has_course = self::checkCourseSubject($child->subject_id);
        }

        return $childs;
    }

    public function checkCourseSubject($subject_id)
    {
        $courses_online = OnlineCourse::where(['subject_id'=>$subject_id,'status'=>1,'isopen'=>1])->exists();
        if ($courses_online)
            return true;
        $courses_offline = OfflineCourse::where(['subject_id'=>$subject_id,'status'=>1,'isopen'=>1])->exists();
        if ($courses_offline)
            return true;
        return false;
    }
}
