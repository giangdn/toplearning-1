<?php

namespace Modules\Quiz\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizCameraImage;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizUserError;

class DoQuizController extends Controller
{
    /**
     * Goto quiz attempt.
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @return view
     * */
    public function index($quiz_id, $part_id, $attempt_id) {
        $user_id = $this->getUserId();
        $user_type = $this->getUserType();
        $quiz = Quiz::findOrFail($quiz_id);

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

        $max_end_date = $quiz->parts()->max('end_date');

        $attempt = $quiz->attempts()
            ->where('id', '=', $attempt_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->firstOrFail();

        $attempt_finish = QuizAttempts::isAttemptFinish($attempt->id);

        $template = $attempt->getTemplateData();

        $questions = $template['questions'];
        $qqcategorys = $template['categories'];

        $qqcategory = [];
        foreach ($qqcategorys as $item) {
            $qqcategory['num_' . $item['num_order']] = $item['name'];
            $qqcategory['percent_' . $item['num_order']] = $item['percent_group'];
        }

        $disabled = 0;
        if (QuizAttempts::isAttemptFinish($attempt_id)) {
            $disabled = 1;
        }

        $quiz_setting = QuizSetting::whereQuizId($quiz_id)->first();

        return view('themes.mobile.frontend.quiz.doquiz', [
            'quiz' => $quiz,
            'part' => $part,
            'attempt' => $attempt,
            'questions' => $questions,
            'attempt_finish' => $attempt_finish,
            'disabled' => $disabled,
            'qqcategory' => $qqcategory,
            'quiz_setting' => $quiz_setting,
            'max_end_date' => $max_end_date,
        ]);
    }

    /**
     * Get question of quiz.
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * */
    public function getQuestionQuiz($quiz_id, $part_id, $attempt_id, Request $request) {
        $user_id = $this->getUserId();
        $user_type = $this->getUserType();

        $quiz = Quiz::findOrFail($quiz_id);

        $attempt = $quiz->attempts()->whereId($attempt_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->firstOrFail();

        $template = $attempt->getTemplateData($quiz->id, $attempt->id);

        $total = count( $template['questions'] );
        $total_page = ceil( $total / $quiz->questions_perpage );

        $page = $request->get('page');
        $offset = ($page - 1) * $quiz->questions_perpage;
        if( $offset < 0 ) $offset = 0;

        $rows = array_slice( $template['questions'], $offset, $quiz->questions_perpage );

        $next = false;
        if ($page < $total_page) {
            $next = true;
        }
        foreach ($rows as $key => $row) {
            $rows[$key] = str_replace(config('app.url'), config('app.mobile_url'), $row);
        }
        /*foreach ($rows as $row) {
            $row->matching = ($row->matching) ? json_decode($row->matching) : '';
            $row->selected = ($row->answer) ? $row->answer : '';
            $row->text_essay = ($row->text_essay) ? json_decode(strip_tags(trim(html_entity_decode($row->text_essay,ENT_QUOTES,'UTF-8'), "\xc2\xa0")), true) : '';
            $row->feedback_ques = ($question && $question->feedback) ? json_decode(strip_tags(trim(html_entity_decode($question->feedback,ENT_QUOTES,'UTF-8'), "\xc2\xa0")), true) : '';
            $row->answer_matching = QuestionAnswer::where('question_id', '=', $row->question_id)
                ->pluck('matching_answer')
                ->toArray();
        }*/

        return response()->json([
            'rows' => $rows,
            'next' => $next
        ]);
    }

    /**
     * Get question of quiz
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * */
    public function saveUserQuiz($quiz_id, $part_id, $attempt_id, Request $request) {
        if (QuizAttempts::isAttemptFinish($attempt_id)) {
            return response()->json([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
            ]);
        }

        $user_type = $this->getUserType();
        $user_id = $this->getUserId();

        $qids = (array) $request->input('q', []);
        $attempt = QuizAttempts::where('id', '=', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->firstOrFail();

        $template = $attempt->getTemplateData();

        if (empty($template)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy template lần thi.',
            ]);
        }

        $questions = $template['questions'];

        foreach ($qids as $index => $question_id){
            $question = $questions[$question_id];

            $anwsers = $request->post('q_' . $question['id']);
            $matching = $request->post('matching_' . $question['id']);

            if ($question['type'] == 'essay'){
                if ($anwsers) {
                    $question['text_essay'] = $anwsers;
                    $question['selected'] = true;
                }
            }

            if ($question['type'] == 'matching'){
                if ($matching) {
                    $question['matching'] = $matching;
                    $question['selected'] = true;
                }
            }

            if ($question['type'] == 'multiple-choise') {
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                }
            }

            $questions[$question_id] = $question;
        }

        $template['questions'] = $questions;

        $attempt->updateTemplateData($template);

        return response()->json([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }

    /**
     * Save quiz user attempt
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @return \Illuminate\Http\JsonResponse
     * @throws
     * */
    public function submitQuiz($quiz_id, $part_id, $attempt_id) {
        $user_type = $this->getUserType();
        $user_id = $this->getUserId();

        $attempt = QuizAttempts::where('id', '=', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->firstOrFail();

        if ($attempt->timefinish > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn đã nộp bài thi này không thể nộp lại',
            ]);
        }

        //QuizAttempts::updateGradeAttempt($attempt->id);
        //QuizTemplate::updateGradeQuiz($quiz_id, $user_id, $user_type);

        \Artisan::call('attempt:complete '. $attempt->id);

        QuizAttempts::where('id', '=', $attempt->id)
            ->update(['timefinish' => time(), 'state' => 'completed']);

        QuizResult::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->update(['timecompleted' => time()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã nộp bài thi thành công',
            'redirect' => route('module.quiz_mobile.doquiz.index', [
                'quiz_id' => $quiz_id,
                'part_id' => $part_id
            ]),
        ]);
    }

    /**
     * Save file question.
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * */
    public function saveFileQuestionEssay($quiz_id, $part_id, $attempt_id, Request $request){
        $attempt = QuizAttempts::findOrFail($attempt_id);

        $question_id = $request->input('question_id');
        $file = $request->file('file_path');

        $storage = \Storage::disk('local');
        $filename = $question_id . '-' . $attempt_id .'.' . $file->getClientOriginalExtension();
        $storage->putFileAs('quiz/' . $quiz_id . '/files', $file, $filename);

        $template = $attempt->getTemplateData();
        $template['questions'][$question_id]['file_essay'] = $filename;

        $attempt->updateTemplateData($template);

        return response()->json([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }

    /**
     * Save quiz user attempt
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws
     * */
    public function saveImage($quiz_id, $part_id, $attempt_id, Request $request) {
        $image = $request->input('image');
        $image = str_replace('data:image/png;base64,', '', $image);

        $file_name = $attempt_id . '-' . $this->getUserId() . '-' . $this->getUserType() .'-' . \Str::random(10) . '.png';
        $file_path = 'quiz/'. $quiz_id . '/camera/' . $file_name;

        $storage = \Storage::disk('local');
        $storage->put($file_path, base64_decode($image));

        QuizCameraImage::create([
            'user_id' => $this->getUserId(),
            'user_type' => $this->getUserType(),
            'attempt_id' => $attempt_id,
            'path' => $file_path
        ]);

        return response()->json([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }

    /**
     * Save error user attempt
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @return \Illuminate\Http\JsonResponse
     * @throws
     * */
    public function saveErrorUser($quiz_id, $part_id, $attempt_id) {
        $user_type = $this->getUserType();
        $user_id = $this->getUserId();

        $count_attempt = QuizUserError::whereQuizId($quiz_id)
            ->whereAttemptId($attempt_id)
            ->wherePartId($part_id)
            ->whereUserId($user_id)
            ->whereType($user_type)
            ->count();

        $error = new QuizUserError();
        $error->attempt_id = $attempt_id;
        $error->quiz_id = $quiz_id;
        $error->part_id = $part_id;
        $error->user_id = $user_id;
        $error->type = $user_type;
        $error->attempt = $count_attempt + 1;
        $error->note = 'Trả lời sai câu hỏi';
        $error->save();

        return json_result([
            'attempt' => $error->attempt,
            'message' => (3 - $error->attempt) == 0 ? 'Bạn hết lượt trả lời' : 'Bạn còn ' . (3 - $error->attempt) . ' lần trả lời',
            'status' => 'error',
        ]);
    }

    public function checkUserQuestion($quiz_id, $part_id, $attempt_id, Request $request){
        $user_id = $this->getUserId();
        $key = $request->key;
        $answer = $request->answer;

        if (!$answer){
            return json_message('Chưa nhập câu hỏi đầy đủ','error');
        }

        $profile = Profile::where('user_id', '=', $user_id)->where('status', '=', 1);
        switch ($key){
            case 'month' :
                $profile->where(\DB::raw('month(dob)'), '=', $answer);
                break;
            case 'day' :
                $profile->where(\DB::raw('day(dob)'), '=', $answer);
                break;
            case 'year' :
                $profile->where(\DB::raw('year(dob)'), '=', $answer);
                break;
            case 'join_company' :
                $profile->where('join_company', '=', date_convert($answer));
                break;
            case 'code' :
                $profile->where('code', '=', $answer);
                break;
            case 'phone' :
                $profile->where('phone', '=', $answer);
                break;
            case 'identity_card' :
                $profile->where('identity_card', '=', $answer);
                break;
            case 'unit_code' :
                $profile->where('unit_code', '=', $answer);
                break;
            case 'title_code' :
                $profile->where('title_code', '=', $answer);
                break;
        }

        $profile = $profile->first();
        if ($profile){
            return json_result([
                'user_id' => $profile ? $profile->user_id : ''
            ]);
        }else{
            return json_message('Thông tin không chính xác','error');
        }
    }

    public function getUserType() {
        if (\Auth::check()) {
            return 1;
        }

        if (\Auth::guard('secondary')->check()) {
            return 2;
        }

        return null;
    }

    public function getUserId() {
        if (\Auth::check()) {
            return \Auth::id();
        }

        if (\Auth::guard('secondary')->check()) {
            return \Auth::guard('secondary')->id();
        }

        return null;
    }

    public function saveUserFlag($quiz_id, $part_id, $attempt_id, Request $request) {
        if (QuizAttempts::isAttemptFinish($attempt_id)) {
            return response()->json([
                'status' => 'success',
                'message' => trans('lageneral.successful_save')
            ]);
        }

        $user_type = $this->getUserType();
        $user_id = $this->getUserId();

        $attempt = QuizAttempts::where('id', '=', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->firstOrFail();

        $template = $attempt->getTemplateData();

        if (empty($template)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy template lần thi.',
            ]);
        }

        $question_id = $request->question_id;
        $questions = $template['questions'];

        $question = $questions[$question_id];
        $question['flag'] = $request->flag;

        $questions[$question_id] = $question;
        $template['questions'] = $questions;

        $attempt->updateTemplateData($template);

        return response()->json([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }
}
