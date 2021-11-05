<?php

namespace Modules\QuizEducatePlan\Entities;

use App\BaseModel;
use App\Profile;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\QuizEducatePlan\Entities\QuizEducatePlan
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
 * @property \Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion[]|null $questions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlanEducatePlan\Entities\QuizEducatePlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereGradeMethor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereLimitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan wherePaperExam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan wherePassScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereQuestionsPerpage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereQuizEducatePlanType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereShuffleAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereShuffleQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereViewResult($value)
 * @mixin \Eloquent
 * @property int|null $type_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlan whereTypeId($value)
 * @property string|null $start_date
 * @property string|null $end_date
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan whereStartDate($value)
 * @property string|null $img
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan whereImg($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart[] $parts
 * @property-read int|null $parts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher[] $teachers
 * @property-read int|null $teachers_count
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan active()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan hasEndPart()
 * @property int $webcam_require
 * @property int $question_require
 * @property int $times_shooting_webcam
 * @property int $times_shooting_question
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\QuizEducatePlan\Entities\QuizEducatePlanAttempts[] $attempts
 * @property-read int|null $attempts_count
 * @property-read int|null $questions_count
 * @property-read \Modules\QuizEducatePlan\Entities\QuizEducatePlanType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan whereMaxAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan whereQuestionRequire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan whereTimesShootingQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan whereTimesShootingWebcam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlan whereWebcamRequire($value)
 */
class QuizEducatePlan extends BaseModel
{
    use ChangeLogs;
    protected $table = 'el_quiz_educate_plan';
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
        'status_convert',
        'quiz_convert_id',
        'approved_by',
        'time_approved',
        'suggest_id',
    ];

    public function parts() {
        return $this->hasMany('Modules\QuizEducatePlan\Entities\QuizEducatePlanPart', 'quiz_id', 'id');
    }

    public function teachers() {
        return $this->hasMany('Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher', 'quiz_id', 'id');
    }

    public function questions() {
        return $this->hasMany('Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion', 'quiz_id', 'id');
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


    public static function isQuizFinish($quiz_id) {
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();

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
