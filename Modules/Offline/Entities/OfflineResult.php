<?php

namespace Modules\Offline\Entities;
use App\Traits\ChangeLogs;
use Modules\Offline\Entities\OfflineAttendance;
use Illuminate\Database\Eloquent\Model;
use Modules\Rating\Entities\RatingCourse;

/**
 * Modules\Offline\Entities\OfflineResult
 *
 * @property int $id
 * @property int $register_id
 * @property int $user_id
 * @property int $course_id
 * @property float|null $percent
 * @property float|null $pass_score
 * @property float|null $score
 * @property int $result
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult wherePassScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineResult whereUserId($value)
 * @mixin \Eloquent
 */
class OfflineResult extends Model
{
    use ChangeLogs;
    protected $table = 'el_offline_result';
    protected $fillable = [
        'register_id',
        'user_id',
        'pass_score',
        'course_id',
        'percent',
        'result',
        'score_1',
        'score_2',
        'score',
        'note',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'register_id' => 'Học viên ghi danh',
            'percent' => 'Phần trăm',
            'result' => 'Kết quả',
            'score' => 'Điểm thi',
            'note' => 'Ghi chú',
        ];
    }

    public static function getPercent($register_id){
        $attendences = OfflineAttendance::where('register_id', '=', $register_id)->get();

        $total_percent = 0;

        if(count($attendences) != 0){

            foreach($attendences as $attendence){
                $total_percent += $attendence->percent;
            }

            $total_percent = $total_percent/count($attendences);
        }

        return number_format($total_percent, 2);
    }

    public static function checkExists($register_id){
        $query = self::query();
        $query->where('register_id', '=', $register_id);
        return $query->exists();
    }

    public function checkComplate() {
        $condition = OfflineCondition::where('course_id', '=', $this->course_id)->first();
        if (empty($condition)) {
            return false;
        }
        $count_condition = 0;
        $count_complate = 0;

        if ($condition->ratio) {
            $count_condition += 1;
            if ($this->percent >= $condition->ratio) {
                $count_complate += 1;
            }
        }

        if ($condition->minscore) {
            $count_condition += 1;
            if ($this->score >= $condition->minscore) {
                $count_complate += 1;
            }
        }

        if ($condition->survey) {
            $count_condition += 1;
            $rating = RatingCourse::checkExists($this->course_id, $this->user_id, 2);
            if ($rating) {
                $count_complate += 1;
            }
        }

        return ($count_complate >= $count_condition);
    }

    public function updateResult () {
        if ($this->checkComplate()) {
            return $this->update(['result' => 1]);
        }

        return $this->update(['result' => 0]);
    }
}
