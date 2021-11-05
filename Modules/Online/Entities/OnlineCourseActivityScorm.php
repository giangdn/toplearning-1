<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseActivityScorm
 *
 * @property int $id
 * @property int $course_id
 * @property string $path
 * @property int $max_attempt 0: Không giới hạn
 * @property int $what_grade 0: Lần cao nhất
 * @property int $max_score
 * @property int $score_required
 * @property int $status_required
 * @property int $min_score_required
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm query()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereMaxAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereMinScoreRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereScoreRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereStatusRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereWhatGrade($value)
 * @mixin \Eloquent
 * @property-read \Modules\Online\Entities\OnlineCourse|null $course
 * @property-read \App\Warehouse|null $warehouse
 * @property-read \Modules\Online\Entities\OnlineCourseActivity|null $course_activity
 * @property-read \Modules\Online\Entities\Scorm|null $scorm
 * @property int $scorm_id
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereScormId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\ActivityScormAttempt[] $attempts
 * @property-read int|null $attempts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\ActivityScormScore[] $scores
 * @property-read int|null $scores_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\ActivityScormUser[] $users
 * @property-read int|null $users_count
 * @property int $status_passed
 * @property int $status_completed
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereStatusCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereStatusPassed($value)
 * @property int $new_attempt_required 1: khi có kết quả, 2: luôn luôn
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityScorm whereNewAttemptRequired($value)
 */
class OnlineCourseActivityScorm extends Model
{
    protected $table = 'el_online_course_activity_scorms';
    protected $fillable = [
        'description',
        'scorm_id',
        'path',
        'max_attempt',
        'what_grade',
        'max_score',
        'score_required',
        'status_passed',
        'status_completed',
        'new_attempt_required',
        'min_score_required',
        'new_attempt_required',
    ];

    public function course_activity() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourseActivity', 'subject_id', 'id');
    }

    public function course() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourse', 'id', 'course_id');
    }

    public function scorm() {
        return $this->hasOne('Modules\Online\Entities\Scorm', 'origin_path', 'path');
    }

    public function warehouse() {
        return $this->hasOne('App\Warehouse', 'file_path', 'path');
    }

    public function attempts() {
        return $this->hasMany('Modules\Online\Entities\ActivityScormAttempt', 'activity_id', 'id');
    }

    public function scores() {
        return $this->hasMany('Modules\Online\Entities\ActivityScormScore', 'activity_id', 'id');
    }

    public function users() {
        return $this->hasMany('Modules\Online\Entities\ActivityScormUser', 'activity_id', 'id');
    }

    /**
     * @param int $user_id
     * @return bool
     * */
    public function checkComplete($user_id, $user_type = 1) {
        $result = [];

        if ($this->score_required == 0) {
            $viewed = OnlineCourseActivityHistory::whereCourseId($this->course_id)
                ->whereCourseActivityId($this->course_activity->id)
                ->whereUserId($user_id)
                ->exists();
            $viewed_scorm_attempt = ActivityScormAttempt::whereActivityId($this->id)
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->exists();

            if ($viewed && $viewed_scorm_attempt) {
                $result[] = true;
            }
            else {
                $result[] = false;
            }
        }
        else {
            $score = null;
            if ($this->scores()
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->count('id') > 0) {

                switch ($this->what_grade) {
                    case 1: $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->max('score');
                    break;
                    case 2: $score = $this->scores()
                            ->whereUserId($user_id)
                            ->where('user_type' ,'=', $user_type)
                            ->sum('score') / $this->scores()
                            ->whereUserId($user_id)
                            ->where('user_type' ,'=', $user_type)
                            ->count('id');
                        break;
                    case 3: $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->first(['score'])->score;
                    break;
                    case 4: $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->orderBy('id', 'DESC')
                        ->first(['score'])->score;
                    break;
                }
            }

            if (is_null($score)) {
                $result[] = false;
            }
            else {
                if ($score >= $this->min_score_required) {
                    $result[] = true;
                }
                else {
                    $result[] = false;
                }
            }
        }

        if ($this->status_passed == 1) {
            $result[] = $this->scores()
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->where('status', 'like', '%passed%')
                ->exists();
        }

        if ($this->status_completed == 1) {
            $result[] = $this->scores()
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->where('status', 'like', '%completed%')
                ->exists();
        }

        /*
         *  Nếu có bất cứ điều kiện nào false thì return false
         * */
        if (in_array(false, $result)) {
            return false;
        }

        return true;
    }

    public function getScoreScorm($user_id, $user_type = 1){
        $score = null;
        if ($this->scores()
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->count('id') > 0) {

            switch ($this->what_grade) {
                case 1: $score = $this->scores()
                    ->whereUserId($user_id)
                    ->where('user_type' ,'=', $user_type)
                    ->max('score');
                    break;
                case 2: $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->sum('score') / $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->count('id');
                    break;
                case 3: $score = $this->scores()
                    ->whereUserId($user_id)
                    ->where('user_type' ,'=', $user_type)
                    ->first(['score'])->score;
                    break;
                case 4: $score = $this->scores()
                    ->whereUserId($user_id)
                    ->where('user_type' ,'=', $user_type)
                    ->orderBy('id', 'DESC')
                    ->first(['score'])->score;
                    break;
            }
        }

        return $score;
    }
}
