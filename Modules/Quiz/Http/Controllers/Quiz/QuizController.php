<?php

namespace Modules\Quiz\Http\Controllers\Quiz;

use App\Models\Categories\Titles;
use App\Profile;
use App\ProfileView;
use App\User;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizGraded;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Entities\QuizUserReview;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Http\Helpers\AttemptTemplate;

class QuizController extends BaseController
{
    public function index($quiz_id, $part_id)
    {
        $user_id = $this->getUserId();
        $user_type = $this->getUserType();

        $quiz = Quiz::whereId($quiz_id)
            ->whereStatus(1)
            ->whereIsOpen(1)
            ->firstOrFail();

        $part = $quiz->parts()
            ->where('id', '=', $part_id)
            ->whereExists(function ($subquery) use ($user_id, $quiz, $user_type) {
                $subquery->select(['a.id'])
                    ->from('el_quiz_register AS a')
                    ->where('a.quiz_id', '=', $quiz->id)
                    ->where('a.user_id', '=', $user_id)
                    ->where('a.type', '=', $user_type)
                    ->whereColumn('a.part_id', '=', 'el_quiz_part.id');
            })->firstOrFail();

        $can_create = $this->canCreateTemplate($quiz_id, $part, $user_id);

        if ($user_type == 1){
            $profile = Profile::select([
                'code',
                \DB::raw('CONCAT(lastname, \' \', firstname) as name'),
                'identity_card',
                'dob',
                'email',
                'phone',
            ])->where('user_id', '=', $user_id)->first();
        }else{
            $profile = QuizUserSecondary::find($user_id);
        }

        $count_quiz_attempts = QuizAttempts::where('quiz_id',$quiz->id)->where('part_id',$part_id)->where('user_id',$user_id)->count();

        $count_quiz_question = QuizQuestion::whereQuizId($quiz_id)->count();

        $descriptions_quiz = [];
        $textlines = explode("\n", $quiz->description);
        for ($i = 0; $i < sizeof($textlines); $i++) {
            $text = str_replace("\r", "", $textlines[$i]);
            if ($text != '') {
                $descriptions_quiz[] = $text;
            }
        }

        return view('quiz::quiz.index', [
            'quiz' => $quiz,
            'part' => $part,
            'can_create' => $can_create,
            'user_type' => $user_type,
            'user_id' => $user_id,
            'profile' => $profile,
            'count_quiz_question' => $count_quiz_question,
            'count_quiz_attempts' => $count_quiz_attempts,
            'descriptions_quiz' => $descriptions_quiz
        ]);
    }

    public function indexByOnline($quiz_id, $part_id)
    {
        $user_id = $this->getUserId();
        $user_type = $this->getUserType();

        $quiz = Quiz::whereId($quiz_id)
            ->whereStatus(1)
            ->whereIsOpen(1)
            ->firstOrFail();

        $part = $quiz->parts()
            ->where('id', '=', $part_id)
            ->whereExists(function ($subquery) use ($user_id, $quiz, $user_type) {
                $subquery->select(['a.id'])
                    ->from('el_quiz_register AS a')
                    ->where('a.quiz_id', '=', $quiz->id)
                    ->where('a.user_id', '=', $user_id)
                    ->where('a.type', '=', $user_type)
                    ->whereColumn('a.part_id', '=', 'el_quiz_part.id');
            })->firstOrFail();

        $can_create = $this->canCreateTemplate($quiz_id, $part, $user_id);

        if ($user_type == 1){
            $profile = Profile::select([
                'code',
                \DB::raw('CONCAT(lastname, \' \', firstname) as name'),
                'identity_card',
                'dob',
                'email',
                'phone',
            ])->where('user_id', '=', $user_id)->first();
        }else{
            $profile = QuizUserSecondary::find($user_id);
        }

        $count_quiz_question = QuizQuestion::whereQuizId($quiz_id)->count();

        return view('quiz::quiz.index_by_online', [
            'quiz' => $quiz,
            'part' => $part,
            'can_create' => $can_create,
            'user_type' => $user_type,
            'user_id' => $user_id,
            'profile' => $profile,
            'count_quiz_question' => $count_quiz_question,
        ]);
    }

    /**
     * Create Quiz attempt for user
     * @param int $quiz_id
     * @param int $part_id
     * @return \Illuminate\Http\JsonResponse
     * */
    public function createQuiz($quiz_id, $part_id) {
        $quiz = Quiz::findOrFail($quiz_id);
        $part = QuizPart::where('quiz_id', '=', $quiz->id)
            ->where('id', '=', $part_id)
            ->firstOrFail();

        $user_id = $this->getUserId();
        $user_type = $this->getUserType();

        if (!$this->canCreateTemplate($quiz_id, $part,$user_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn không được làm thêm bài thi này',
            ]);
        }

        $attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->where('timefinish', '=', 0)
            ->orderBy('id', 'DESC')
            ->first();

        if ($attempt) {
            $end_time = $attempt->timestart + ($quiz->limit_time * 60);
            if ($end_time < time()) {
                $attempt = null;
            }
        }

        if (empty($attempt)) {

            $count_attempt = QuizAttempts::whereQuizId($quiz_id)
                ->wherePartId($part_id)
                ->whereUserId($user_id)
                ->whereType($user_type)
                ->count();

            $attempt = new QuizAttempts();
            $attempt->quiz_id = $quiz_id;
            $attempt->part_id = $part_id;
            $attempt->user_id = $user_id;
            $attempt->type = $user_type;
            $attempt->attempt = $count_attempt + 1;
            $attempt->state = 'inprogress';
            $attempt->timestart = time();

            $template = new AttemptTemplate($attempt);
            $template->create();

            QuizResult::whereQuizId($quiz_id)
                ->whereUserId($user_id)
                ->firstOrCreate([
                    'quiz_id' => $quiz_id,
                    'user_id' => $user_id,
                    'type' => $user_type,
                    'result' => 0,
                ]);

            return response()->json([
                'status' => 'success',
                'redirect' => route('module.quiz.doquiz.do_quiz', [
                    'quiz_id' => $quiz_id,
                    'part_id' => $part_id,
                    'attempt_id' => $attempt->id
                ]),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'redirect' => route('module.quiz.doquiz.do_quiz', [
                'quiz_id' => $quiz_id,
                'part_id' => $part_id,
                'attempt_id' => $attempt->id
            ]),
        ]);
    }

    /**
     * Get question of quiz
     * @param int $quiz_id
     * @param int $part_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * */
    public function getAttemptHistory($quiz_id, $part_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_id = $request->user_id ? $request->user_id : Quiz::getUserId();
        $user_type = $request->user_type ? $request->user_type : Quiz::getUserType();
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();
        $part = QuizPart::where('quiz_id', '=', $quiz->id)->pluck('end_date')->toArray();
        $max_end_date = MAX($part);
        $quiz_setting = QuizSetting::where('quiz_id', '=', $quiz_id)->first();
        $graded = QuizGraded::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', $user_type)
            ->first();

        $query = QuizAttempts::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('user_id', '=', $user_id);
        $query->where('type', '=', $user_type);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        $check_essay = 0;
        foreach ($rows as $row) {
            $update_attempt = QuizUpdateAttempts::query()
                ->where('attempt_id', '=', $row->id)
                ->where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('type', '=', $user_type)
                ->first();
            $questions = $update_attempt ? json_decode($update_attempt['questions'], true) : [];
            foreach ($questions as $question){
                if ($question['type'] == 'essay' || $question['type'] == 'fill_in'){
                    $check_essay = 1;
                }
            }

            $status = '';
            switch ($row->state) {
                case 'inprogress': $status = 'Đang làm bài'; break;
                case 'completed': $status = 'Hoàn thành'; break;
            }

            $row->start_date = date('H:i d/m/Y', $row->timestart);
            $row->end_date = $row->timefinish > 0 ? date('H:i d/m/Y', $row->timefinish) : '';

            if ($check_essay == 1 && !$graded){
                $row->grade = '_';
            }else{
                $row->grade = $quiz_setting ? ($quiz_setting->after_test_score == 1 || ($quiz_setting->exam_closed_score == 1 && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d'))) ? round($row->sumgrades, 2) : '_' : '_';
            }


            $row->status = $status;

            $row->after_review = ($quiz_setting == null)? 0 : $quiz_setting->after_test_review_test;

            $row->closed_review = $quiz_setting && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d') ? $quiz_setting->exam_closed_review_test : 0;

            if ($quiz_setting){
                $row->review_link = ($quiz_setting->after_test_review_test == 1 || $quiz_setting->exam_closed_review_test == 1) ? route('module.quiz.doquiz.do_quiz', ['quiz_id' => $quiz_id, 'part_id' => $row->part_id, 'attempt_id' => $row->id]): '';
            }
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function canCreateTemplate($quiz_id, $part, $user_id)
    {
        $quiz = Quiz::where('id', '=', $quiz_id)->first();
        $user_attempt = QuizAttempts::countQuizAttempt($quiz_id, $user_id);

        $attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->first();

        if (empty($quiz)) {
            return false;
        }
        if ($part->end_date && strtotime($part->end_date) < time())
            return false;
        if (time()< strtotime($part->start_date))
            return false;
        if ($user_attempt < $quiz->max_attempts || $quiz->max_attempts == 0 || (($attempt->timestart + ($quiz->limit_time * 60)) > time() && $attempt->timefinish == 0)) {
            return true;
        }

        return false;
    }

    public function userReviewQuiz($quiz_id, $part_id, Request $request){
        $user_id = Quiz::getUserId();
        $user_type = Quiz::getUserType();
        $content = $request->input('content_review');

        $title_id = null;
        $title_name = null;
        $unit_id = null;
        $unit_name = null;
        $parent_unit_id = null;
        $parent_unit_name = null;

        if ($user_type == 1){
            $profile = Profile::query()
            ->select([
                'code',
                \DB::raw('CONCAT(lastname, \' \', firstname) as name'),
                'email',
            ])->where('user_id', '=', $user_id)->first();

            $title = $profile->titles;
            $unit = $profile->unit;
            $parent_unit = @$unit->parent;

            $title_id = @$title->id;
            $title_name = @$title->name;
            $unit_id = @$unit->id;
            $unit_name = @$unit->name;
            $parent_unit_id = @$parent_unit->id;
            $parent_unit_name = @$parent_unit->name;

            $username = User::find($user_id)->username;
        }else{
            $profile = QuizUserSecondary::find($user_id);

            $username = $profile->username;
        }

        $model = new QuizUserReview();
        $model->quiz_id = $quiz_id;
        $model->part_id = $part_id;
        $model->user_id = $user_id;
        $model->user_type = $user_type;
        $model->user_code = $profile->code;
        $model->full_name = $profile->name;
        $model->username = $username;
        $model->email = $profile->email;
        $model->title_id = $title_id;
        $model->title_name = $title_name;
        $model->unit_id = $unit_id;
        $model->unit_name = $unit_name;
        $model->parent_unit_id = $parent_unit_id;
        $model->parent_unit_name = $parent_unit_name;
        $model->content = $content;
        $model->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cảm ơn bạn đã góp ý.',
            'redirect' => route('module.quiz.doquiz.index', [
                'quiz_id' => $quiz_id,
                'part_id' => $part_id,
            ]),
        ]);
    }
}
