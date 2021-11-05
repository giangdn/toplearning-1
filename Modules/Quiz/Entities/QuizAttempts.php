<?php

namespace Modules\Quiz\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizAttempts
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $part_id
 * @property int $user_id
 * @property int $type
 * @property int $attempt
 * @property string $state
 * @property int $timestart
 * @property int $timefinish
 * @property float $sumgrades
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Modules\Quiz\Entities\Quiz|null $quiz
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereSumgrades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereTimefinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereTimestart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttempts whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizUserError[] $erroruser
 * @property-read int|null $erroruser_count
 * @property-read \Modules\Quiz\Entities\QuizAttemptsTemplate|null $template
 */
class QuizAttempts extends BaseModel
{
    protected $table = 'el_quiz_attempts';
    protected $fillable = [
        'sumgrades',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function quiz() {
        return $this->hasOne('Modules\Quiz\Entities\Quiz', 'id', 'quiz_id');
    }

    public function erroruser() {
        return $this->hasMany('Modules\Quiz\Entities\QuizUserError', 'attempt_id', 'id');
    }

    public function template() {
        return $this->hasOne('Modules\Quiz\Entities\QuizAttemptsTemplate', 'attempt_id', 'id');
    }

    public function getTemplateData() {
        $storage = \Storage::disk('local');
        $template = 'quiz/' . $this->quiz_id . '/attempts/attempt-' . $this->id .'.json';

        if ($storage->exists($template)) {
            return json_decode($storage->get($template), true);
        }
        return null;
    }

    public function updateTemplateData($template) {
        $storage = \Storage::disk('local');
        $template_path = 'quiz/' . $this->quiz_id . '/attempts/attempt-' . $this->id .'.json';
        $storage->put($template_path, json_encode($template));
    }

    public static function isAttemptFinish($attempt_id) {
        $attempt = QuizAttempts::where('id', '=', $attempt_id)->first();
        $quiz = Quiz::where('id', '=', $attempt->quiz_id)->first();
        $part = QuizPart::where('id', '=', $attempt->part_id)->first();

        if (empty($attempt) || empty($quiz) || empty($part)) {
            return true;
        }

        if ($attempt->timefinish > 0) {
            return true;
        }

        if ($quiz->quiz_type != 1){
            if ($part->end_date < date('Y-m-d H:i:s')) {
                return true;
            }
        }

        if (($attempt->timestart + ($quiz->limit_time * 60)) < time()) {
            return true;
        }

        return false;
    }

    public static function countQuizAttempt($quiz_id, $user_id) {
        return self::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->count();
    }

    public static function updateGradeAttempt($attempt_id) {
//        $attempt = QuizAttempts::where('id', '=', $attempt_id)->first();
        $atemp = QuizAttemptsTemplate::where('attempt_id', '=', $attempt_id)->first();
        $grade = QuizTemplateQuestion::getGradeUser($atemp->template_id);
        return self::where('id', '=', $attempt_id)->update(['sumgrades' => $grade]);
    }

    public static function updateGradeAttemptByTeacher($attempt_id) {
//        $attempt = QuizAttempts::where('id', '=', $attempt_id)->first();
        $atemp = QuizAttemptsTemplate::where('attempt_id', '=', $attempt_id)->first();
        $grade = QuizTemplateQuestion::getGradeUserByTeacher($atemp->template_id);

        return self::where('id', '=', $attempt_id)->update(['sumgrades' => $grade]);
    }
}
