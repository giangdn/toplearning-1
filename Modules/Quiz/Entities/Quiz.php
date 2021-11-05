<?php

namespace Modules\Quiz\Entities;

use App\BaseModel;
use App\Profile;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\Quiz
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $unit_id
 * @property int $limit_time
 * @property int $view_result
 * @property int $shuffle_answers
 * @property int $shuffle_question
 * @property int $paper_exam
 * @property int $questions_perpage
 * @property float $pass_score
 * @property float $max_score
 * @property string|null $description
 * @property int $max_attempts
 * @property int $grade_methor
 * @property int $review
 * @property int $is_open
 * @property int $status
 * @property int $course_id
 * @property int $course_type
 * @property int $quiz_type
 * @property int $created_by
 * @property int $updated_by
 * @property \Modules\Quiz\Entities\QuizQuestion[]|null $questions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereGradeMethor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereLimitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz wherePaperExam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz wherePassScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereQuestionsPerpage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereQuizType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereShuffleAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereShuffleQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereViewResult($value)
 * @mixin \Eloquent
 * @property int|null $type_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\Quiz whereTypeId($value)
 * @property string|null $start_date
 * @property string|null $end_date
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereStartDate($value)
 * @property string|null $img
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereImg($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizPart[] $parts
 * @property-read int|null $parts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizTeacher[] $teachers
 * @property-read int|null $teachers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz active()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz hasEndPart()
 * @property int $webcam_require
 * @property int $question_require
 * @property int $times_shooting_webcam
 * @property int $times_shooting_question
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizAttempts[] $attempts
 * @property-read int|null $attempts_count
 * @property-read int|null $questions_count
 * @property-read \Modules\Quiz\Entities\QuizType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereMaxAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereQuestionRequire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereTimesShootingQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereTimesShootingWebcam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereWebcamRequire($value)
 */
class Quiz extends BaseModel
{
    use ChangeLogs;
    protected $table = 'el_quiz';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'unit_id',
        'start_date',
        'end_date',
        'limit_time',
        'view_result',
        'shuffle_answers',
        'shuffle_question',
        'paper_exam',
        'questions_perpage',
        'is_open',
        'status',
        'pass_score',
        'max_score',
        'description',
        'max_attempts',
        'grade_methor',
        'created_by',
        'updated_by',
        'course_id',
        'course_type',
        'quiz_type',
        'type_id',
        'img',
        'webcam_require',
        'question_require',
        'times_shooting_webcam',
        'times_shooting_question',
        'quiz_template_id',
        'quiz_location',
        'show_name',
        'approved_step',
    ];

    public function parts() {
        return $this->hasMany('Modules\Quiz\Entities\QuizPart', 'quiz_id', 'id');
    }

    public function teachers() {
        return $this->hasMany('Modules\Quiz\Entities\QuizTeacher', 'quiz_id', 'id');
    }

    public function attempts() {
        return $this->hasMany('Modules\Quiz\Entities\QuizAttempts', 'quiz_id', 'id');
    }

    public function questions() {
        return $this->hasMany('Modules\Quiz\Entities\QuizQuestion', 'quiz_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(QuizType::class,'type_id');
    }
    /**
     * Scope a query to only quiz active.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) {
        return $query->where('status', '=', 1);
    }

    /**
     * Scope a query to only quiz Finished (Enddate < now()).
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasEndPart($query) {
        return $query->whereHas('parts', function ($subquery) {
            $subquery->where(function ($where) {
                    $where->orWhere('end_date', '<', now());
                    $where->orWhereNull('end_date');
                });
        });
    }

    public static function getAttributeName() {
        return [
            'code' => 'Mã kỳ thi',
            'name' => 'Tên kỳ thi',
            'start_date' => 'Thời gian bắt đầu',
            'end_date' => 'Thời gian kết thúc',
            'limit_time' => 'Thời gian làm bài',
            'view_result' => 'Xem kết quả',
            'shuffle_answers' => 'Xáo trộn đáp án',
            'shuffle_question' => 'Xáo trộn câu hỏi',
            'paper_exam' => 'Thi giấy',
            'questions_perpage' => 'Số câu hỏi 1 trang',
            'is_open' => trans('lacore.open'),
            'status' => 'Trạng thái',
            'pass_score' => 'Điểm chuẩn',
            'max_score' => 'Điểm tối đa',
            'description' => 'Nội dung',
            'max_attempts' => 'Số lần làm bài',
            'grade_methor' => 'Cách tính điểm',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor'),
            'course_id' => trans('lacourse.course_code'),
            'course_type' => 'Loại khóa học',
            'quiz_type' => 'Loại kỳ thi',
        ];
    }

    public static function countQuizUser($user_id = null, $user_type = null) {
        if (empty($user_id)) {
            $user_id = Quiz::getUserId();
        }

        if (empty($user_type)) {
            $user_type = Quiz::getUserType();
        }

        $query = QuizPart::query();
        $query->select([
            'a.id',
        ]);
        $query->from('el_quiz AS a')
            ->join('el_quiz_part AS b', function ($subquery) use ($user_id) {
                $subquery->on('b.quiz_id', '=', 'a.id')
                    ->whereIn('b.id', function ($subquery2) use ($user_id) {
                        $subquery2->select(['part_id'])
                            ->from('el_quiz_register')
                            ->where('user_id', '=', $user_id)
                            ->whereColumn('quiz_id', '=', 'a.id');
                    });
            })
            ->where('a.status', '=', 1)
            ->where('a.is_open', '=', 1)
            ->where(function ($sub){
                $sub->orWhere('a.quiz_type', '=', 3);
                $sub->orWhereIn('a.id', function ($subquery){
                    $subquery->select(['quiz_id'])
                        ->from('el_offline_course')
                        ->whereNotNull('quiz_id')
                        ->where('status', '=', 1)
                        ->where('isopen', '=', 1);
                });
            })
            ->whereIn('a.id', function ($subquery) use ($user_id, $user_type){
                $subquery->select(['quiz_id'])
                    ->from('el_quiz_register')
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', $user_type);
            });
        return $query->count();
    }

    public static function isQuizFinish($quiz_id) {
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();

    }

    public static function getUserType() {
        if (\Auth::check()) {
            return 1;
        }

        if (\Auth::guard('secondary')->check()) {
            return 2;
        }

        return null;
    }

    public static function getUserId() {
        if (\Auth::check()) {
            return \Auth::id();
        }

        if (\Auth::guard('secondary')->check()) {
            return \Auth::guard('secondary')->id();
        }

        return null;
    }

    public static function getStatusUser($quiz_id) {
        $user_id = Quiz::getUserId();
        $user_type = Quiz::getUserType();
        $query = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type);

        if (!$query->exists()) {
            return 0;
        }

        $finishd = $query->where('timefinish', '>', 0);
        if ($finishd->exists()) {
            return 1;
        }

        $process = $query->where('timefinish', '=', 0);
        if ($process->exists()) {
            return 2;
        }

        return 0;
    }

    public static function getMyQuiz($userId = null)
    {

    }

    public static function countQuiz() {
        return self::where('quiz_type','=',3)->count();
    }

    public static function getLastestQuiz($limit = 5){
        $query = self::query();
        $query->orderBy('created_at', 'DESC');
        $query->limit($limit);
        return $query->get();
    }

}
