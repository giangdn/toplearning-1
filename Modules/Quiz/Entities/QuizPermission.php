<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizPermission
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPermission query()
 * @mixin \Eloquent
 */
class QuizPermission extends Model
{
    public static function createQuiz($quiz = null) {
        return true;
    }

    public static function editQuiz($quiz = null) {
        return true;
    }

    public static function saveQuiz($quiz) {
        if (empty($quiz->id)) {
            return self::createQuiz();
        }

        if ($quiz->id) {
            return self::editQuiz($quiz);
        }

        return false;
    }

    public static function addQuestionQuiz($quiz = null) {
        return true;
    }

    public static function addRegisterQuiz($quiz = null) {
        return true;
    }

    public static function addUserSecondaryQuiz($quiz = null) {
        return true;
    }

    public static function viewResultQuiz($quiz = null) {
        return true;
    }

    public static function exportQuiz($quiz = null) {
        return true;
    }

    public static function saveGradeResult($quiz = null) {
        return true;
    }

    public static function saveReexamineResult($quiz = null) {
        return true;
    }

    public static function exportResult($quiz = null) {
        return true;
    }

}
