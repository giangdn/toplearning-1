<?php

namespace Modules\Online\Entities;

use App\Models\Categories\TrainingTeacher;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizResult;
use Modules\VirtualClassroom\Entities\VirtualClassroom;
use Modules\VirtualClassroom\Helpers\BBBApi;

/**
 * Modules\Online\Entities\OnlineCourseActivity
 *
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property int $activity_id
 * @property int $subject_id
 * @property int $num_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity whereNumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivity whereStatus($value)
 * @property-read \Modules\Online\Entities\OnlineActivity|null $activity
 * @property-read \Modules\Online\Entities\OnlineCourse|null $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineCourseActivityCompletion[] $activity_completions
 * @property-read int|null $activity_completions_count
 */
class OnlineCourseActivity extends Model
{
    protected $table = 'el_online_course_activity';
    protected $fillable = [
        'name',
        'activity_id',
        'subject_id',
        'num_order',
        'setting_complete_course_activity_id',
        'setting_start_date',
        'setting_end_date',
        'setting_score_course_activity_id',
        'setting_min_score',
        'setting_max_score'
    ];

    public function course() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourse', 'id', 'course_id');
    }

    public function activity() {
        return $this->hasOne('Modules\Online\Entities\OnlineActivity', 'id', 'activity_id');
    }

    public function activity_completions() {
        return $this->hasMany('Modules\Online\Entities\OnlineCourseActivityCompletion', 'activity_id', 'id');
    }
    /**
     * Get activity module.
     * @return OnlineCourseActivityScorm|OnlineCourseActivityQuiz|OnlineCourseActivityFile|OnlineCourseActivityUrl
     * */
    public function activity_module() {
        $module = null;
        switch ($this->activity_id) {
            case 1: $module = OnlineCourseActivityScorm::find($this->subject_id);break;
            case 2: $module = OnlineCourseActivityQuiz::find($this->subject_id);break;
            case 3: $module = OnlineCourseActivityFile::find($this->subject_id);break;
            case 4: $module = OnlineCourseActivityUrl::find($this->subject_id);break;
            case 5: $module = OnlineCourseActivityVideo::find($this->subject_id);break;
            case 6: $module = OnlineCourseActivityBbb::find($this->subject_id);break;
        }

        return $module;
    }

    public function getLinkScorm($lesson) {
        return route('module.online.scorm', [
            $this->course_id,
            $this->subject_id,
            'lesson' => $lesson
        ]);
    }

    public function getLinkQuiz() {
        $user_type = getUserType();
        $user_id = getUserId();

        $part =  QuizPart::where('quiz_id', '=', $this->subject_id)
            ->whereIn('id', function ($subquery) use ($user_id, $user_type) {
                $subquery->select(['a.part_id'])
                    ->from('el_quiz_register AS a')
                    ->join('el_quiz_part AS b', 'b.id', '=', 'a.part_id')
                    ->where('a.quiz_id', '=', $this->subject_id)
                    ->where('a.user_id', '=', $user_id)
                    ->where('a.type', '=', $user_type)
                    ->where(function ($where){
                        $where->orWhere('b.end_date', '>', date('Y-m-d H:i:s'));
                        $where->orWhereNull('b.end_date');
                    });
            })->firstOrFail();

        if (url_mobile()){
            return route('module.quiz_mobile.doquiz.index', [$this->subject_id, $part->id]);
        }

        return route('module.quiz.doquiz.index', [$this->subject_id, $part->id]);
    }

    public function getLinkFile() {
        $file = OnlineCourseActivityFile::where('id', '=', $this->subject_id)->first();
        $file_path = upload_file(explode('|', $file->path)[0]);

        if (url_mobile()){
            $file_path = str_replace(config('app.url'), config('app.mobile_url'), $file_path);
        }
        // dd($file_path);
        return route('module.online.view_pdf', [$this->course_id]).'?path='. $file_path;
    }

    public function getLinkUrl() {
        $url = OnlineCourseActivityUrl::find($this->subject_id, ['url']);
        return $url->url;
    }

    public function getLinkVideo() {
        $url = OnlineCourseActivityVideo::find($this->subject_id);
        return $url->getLinkPlay();
    }

    public function getLinkBBB() {
        $traning_teacher = TrainingTeacher::where('user_id','=', \Auth::id())->first();
        if (BBBApi::isConnect()) {
            $bbb_info = VirtualClassroom::where('id', '=', $this->subject_id)->first();

            $bbb = new BBBApi($bbb_info->code, $bbb_info->name);

            if (!$bbb->isRuning()) {
                $bbb->create();
            }

            if ($traning_teacher){
                return $bbb->join(\Auth::id(), 'moderator');
            }

            return $bbb->join(\Auth::id());
        }

        return false;
    }

    public function getLinkQuizCourse($lesson){
        $user_type = getUserType();
        $user_id = getUserId();

        $part =  QuizPart::where('quiz_id', '=', $this->subject_id)
            ->whereIn('id', function ($subquery) use ($user_id, $user_type) {
                $subquery->select(['a.part_id'])
                    ->from('el_quiz_register AS a')
                    ->join('el_quiz_part AS b', 'b.id', '=', 'a.part_id')
                    ->where('a.quiz_id', '=', $this->subject_id)
                    ->where('a.user_id', '=', $user_id)
                    ->where('a.type', '=', $user_type)
                    ->where(function ($where){
                        $where->orWhere('b.end_date', '>', date('Y-m-d H:i:s'));
                        $where->orWhereNull('b.end_date');
                    });
            })->first();
        if (!empty($part)) {

            if (url_mobile()){
                return route('module.quiz_mobile.doquiz.index_by_online', [$this->subject_id, $part->id]);
            }

            return route('module.quiz.doquiz.index_by_online', [$this->subject_id, $part->id]);
        } else {
            return '';
        }

    }

    public function getLink($lesson) {
        $link = null;

        switch ($this->activity_id) {
            case 1: $link = $this->getLinkScorm($lesson);break;
            case 2: $link = $this->getLinkQuiz();break;
            case 3: $link = $this->getLinkFile();break;
            case 4: $link = $this->getLinkUrl();break;
            case 5: $link = $this->getLinkVideo();break;
            case 6: $link = $this->getLinkBBB();break;
        }

        if (empty($link)) {
            return false;
        }

        if ($this->activity->openEmbed()) {
            return route('module.online.embed', [$this->course_id, 'lesson' => $lesson]) .'?url='. urlencode($link) . '&title=' . urlencode($this->name);
        }

        return $link;
    }

    /**
     * Is completed activity by user id.
     * @param int $user_id
     * @return bool
     * */
    public function isComplete($user_id, $user_type = 1) {
        return $this->activity_completions()
            ->whereUserId($user_id)
            ->where('user_type', '=', $user_type)
            ->whereStatus(1)
            ->exists();
    }

    /**
     * Check completed activity by user id.
     * @param int $user_id
     * @return bool
     * */
    public function checkComplete($user_id = null, $user_type = null) {
        $user_id = $user_id ?? getUserId();
        $user_type = $user_type ?? getUserType();

        $activity = OnlineActivity::where('id', '=', $this->activity_id)->first();

        if (empty($activity)) {
            return false;
        }

        if (in_array($activity->code, ['url', 'file', 'video', 'virtualclassroom'])) {

            if (OnlineCourseActivityHistory::where('course_id', '=', $this->course_id)
                ->where('course_activity_id', '=', $this->id)
                ->where('user_id', '=', $user_id)
                ->where('user_type', '=', $user_type)
                ->exists()) {
                return true;
            }

            return false;
        }

        if ($activity->code == 'quiz') {
            if ($this->subject_id) {
                $quiz = Quiz::find($this->subject_id);
                if (empty($quiz)) {
                    return false;
                }

                $score = QuizResult::where('quiz_id', '=', $quiz->id)
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', $user_type)
                    ->first();

                if ($score){
                    return (object) [
                        'pass_score' => $quiz->pass_score,
                        'score' => isset($score->reexamine) ? $score->reexamine : (isset($score->grade) ? $score->grade : 0),
                    ];
                }
                return false;
            }

            return false;
        }

        if ($activity->code == 'scorm') {

            $scorm = OnlineCourseActivityScorm::find($this->subject_id);
            if ($scorm) {
                return $scorm->checkComplete($user_id, $user_type);
            }

            return false;
        }

        return false;
    }

    public static function getByCourse($course_id, $activity_id = null) {
        $query = OnlineCourseActivity::query();
        $query->select([
            'a.id',
            'a.name',
            'a.subject_id',
            'a.lesson_id',
            'b.id AS activity_id',
            'b.code AS activity_code',
            'b.icon',
            'b.name AS activity_name',
            'a.subject_id AS subject_id',
            'a.num_order',
            'a.status'
        ]);
        $query->from('el_online_course_activity AS a')
            ->join('el_online_activity AS b', 'b.id', '=', 'a.activity_id')
            ->where('a.course_id', '=', $course_id)
            ->orderBy('a.num_order', 'ASC');
        if ($activity_id){
            $query->where('a.activity_id', '=', $activity_id);
        }

        if ($query->exists()) {
            return $query->get();
        }

        return [];
    }

    public function checkSettingActivity($user_id = null){
        $user_id = $user_id ?? \Auth::id();
        if (!$this->setting_complete_course_activity_id && !$this->setting_start_date && !$this->setting_end_date && !$this->setting_score_course_activity_id){
            return true;
        }

        $result = [];

        if ($this->setting_complete_course_activity_id){
            $course_activity = self::whereId($this->setting_complete_course_activity_id)->first();
            $complete = $course_activity->isComplete($user_id);
            if ($complete){
                $result[] = true;
            }else{
                $result[] = false;
            }
        }

        if ($this->setting_start_date){
            if ($this->setting_start_date <= date('Y-m-d H:i:s')){
                $result[] = true;
            }else{
                $result[] = false;
            }
        }

        if ($this->setting_end_date){
            if ($this->setting_end_date >= date('Y-m-d H:i:s')){
                $result[] = true;
            }else{
                $result[] = false;
            }
        }

        if ($this->setting_score_course_activity_id){
            $score = null;
            $course_activity = self::whereId($this->setting_score_course_activity_id)->first();
            if ($course_activity && $course_activity->activity_id == 1){
                $activity_scorm = OnlineCourseActivityScorm::find($course_activity->subject_id);
                $score = $activity_scorm->getScoreScorm($user_id);
            }
            if ($course_activity && $course_activity->activity_id == 2){
                $quiz_result = QuizResult::where('quiz_id', '=', $course_activity->subject_id)
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', 1)
                    ->first();
                if ($quiz_result){
                    $score = isset($quiz_result->reexamine) ? $quiz_result->reexamine : (isset($quiz_result->grade) ? $quiz_result->grade : null);
                }
            }

            if (!is_null($score)){
                if ($this->setting_min_score){
                    if ($this->setting_min_score <= $score){
                        $result[] = true;
                    }else{
                        $result[] = false;
                    }
                }

                if ($this->setting_max_score){
                    if ($score < $this->setting_max_score){
                        $result[] = true;
                    }else{
                        $result[] = false;
                    }
                }
            }else{
                $result[] = false;
            }
        }

        if (in_array(false, $result)) {
            return false;
        }

        return true;
    }
}
