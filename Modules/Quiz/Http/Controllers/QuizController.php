<?php

namespace Modules\Quiz\Http\Controllers;

use App\Automail;
use App\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizAttemptsTemplate;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestionAnswerSelected;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizTemplate;
use Modules\Quiz\Entities\QuizTemplateQuestion;
use Modules\Quiz\Entities\QuizUserSecondary;

class QuizController extends Controller
{
    public function index($quiz_id, $part_id)
    {
        $user_id = Quiz::getUserId();
        $user_type = Quiz::getUserType();
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

        $can_create = QuizTemplate::canCreateTemplate($quiz_id, $part, $user_id);

        if (url_mobile()){
            return view('themes.mobile.frontend.quiz.goquiz',[
                'quiz' => $quiz,
                'part' => $part,
                'can_create' => $can_create
            ]);
        }

        return view('quiz::quiz.index', [
            'quiz' => $quiz,
            'part' => $part,
            'can_create' => $can_create,
        ]);
    }

    /**
     * Goto quiz attempt.
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @return view
     * */
    public function doQuiz($quiz_id, $part_id, $attempt_id) {
        $user_id = Quiz::getUserId();
        $user_type = Quiz::getUserType();
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

        $attempt_temp = QuizAttemptsTemplate::where('attempt_id', '=', $attempt->id)->firstOrFail();
        $check_query = QuizQuestionAnswerSelected::where('template_id', '=', $attempt_temp->template_id)->first();
        if ($check_query){
            $questions = QuizQuestionAnswerSelected::where('template_id', '=', $attempt_temp->template_id)->get(['id', 'template_id']);
            $isselected = function ($question_id, $template_id) {
                return QuizQuestionAnswerSelected::isSelected($question_id, $template_id);
            };
        }else{
            $questions = QuizTemplateQuestion::where('template_id', '=', $attempt_temp->template_id)->get(['id', 'template_id']);
            $isselected = function ($question_id, $template_id) {
                return QuizTemplateQuestion::isSelected($question_id, $template_id);
            };
        }

        $attempt_finish = QuizAttempts::isAttemptFinish($attempt->id);

        $qqcategorys = QuizQuestionCategory::where('quiz_id', '=', $quiz_id)->get(['name', 'num_order', 'percent_group']);
        $qqcategory = [];
        foreach ($qqcategorys as $item) {
            $qqcategory['num_' . $item->num_order] = $item->name;
            $qqcategory['percent_' . $item->num_order] = $item->percent_group;
        }

        $disabled = 0;
        if (QuizAttempts::isAttemptFinish($attempt_id)) {
            $disabled = 1;
        }

        $quiz_setting = QuizSetting::where('quiz_id', '=', $quiz_id)->first();

        if (url_mobile()){
            return view('themes.mobile.frontend.quiz.doquiz', [
                'quiz' => $quiz,
                'part' => $part,
                'attempt' => $attempt,
                'questions' => $questions,
                'attempt_finish' => $attempt_finish,
                'disabled' => $disabled,
                'isselected' => $isselected,
                'qqcategory' => $qqcategory,
                'quiz_setting' => $quiz_setting,
                'max_end_date' => $max_end_date,
            ]);
        }

        return view('quiz::quiz.doquiz', [
            'quiz' => $quiz,
            'part' => $part,
            'attempt' => $attempt,
            'questions' => $questions,
            'attempt_finish' => $attempt_finish,
            'disabled' => $disabled,
            'isselected' => $isselected,
            'qqcategory' => $qqcategory,
            'quiz_setting' => $quiz_setting,
            'max_end_date' => $max_end_date,
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

        $user_id = Quiz::getUserId();
        $user_type = Quiz::getUserType();

        if (!QuizTemplate::canCreateTemplate($quiz_id, $part,$user_id)) {
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
            $template_id = QuizTemplate::selectTemplateQuizRand($quiz_id, $part_id);

            $count_attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('part_id', '=', $part_id)
                ->where('user_id', '=', $user_id)
                ->where('type', '=', $user_type)
                ->count();

            $attempt = new QuizAttempts();
            $attempt->quiz_id = $quiz_id;
            $attempt->part_id = $part_id;
            $attempt->user_id = $user_id;
            $attempt->type = $user_type;
            $attempt->attempt = $count_attempt+1;
            $attempt->state = 'inprogress';
            $attempt->timestart = time();
            $attempt->save();

            if ($template_id) {
                $quiz_result = QuizResult::where('quiz_id', '=', $quiz_id)
                    ->where('user_id', '=', $user_id);

                if (!$quiz_result->exists()){
                    $quiz_result = new QuizResult();
                    $quiz_result->quiz_id = $quiz_id;
                    $quiz_result->user_id = $user_id;
                    $quiz_result->type = $user_type;
                    $quiz_result->result = 0;
                    $quiz_result->save();
                }

                $atttemplate = new QuizAttemptsTemplate();
                $atttemplate->attempt_id = $attempt->id;
                $atttemplate->template_id = $template_id;
                $atttemplate->save();

                return response()->json([
                    'status' => 'success',
                    'redirect' => route('module.quiz.doquiz.do_quiz', [
                        'quiz_id' => $quiz_id,
                        'part_id' => $part_id,
                        'attempt_id' => $attempt->id
                    ]),
                ]);
            }
        } else{
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
            'status' => 'error',
            'message' => 'Lỗi tạo để thi',
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

        $query = QuizAttempts::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('user_id', '=', $user_id);
        $query->where('type', '=', $user_type);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $status = '';
            switch ($row->state) {
                case 'inprogress': $status = 'Đang làm bài'; break;
                case 'completed': $status = 'Hoàn thành'; break;
            }

            $row->start_date = date('H:i d/m/Y', $row->timestart);
            $row->end_date = $row->timefinish > 0 ? date('H:i d/m/Y', $row->timefinish) : '';

            $row->grade = $quiz_setting ? ($quiz_setting->after_test_score == 1 || ($quiz_setting->exam_closed_score == 1 && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d'))) ? round($row->sumgrades, 2) : '_' : '_';

            $row->status = $status;

            $row->after_review = ($quiz_setting == null)? 0 : $quiz_setting->after_test_review_test;

            $row->closed_review = $quiz_setting && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d') ? $quiz_setting->exam_closed_review_test : 0;

            if ($quiz_setting){
                $row->review_link = ($quiz_setting->after_test_review_test == 1 || $quiz_setting->exam_closed_review_test == 1) ? route('module.quiz.doquiz.do_quiz', ['quiz_id' => $quiz_id, 'part_id' => $row->part_id, 'attempt_id' => $row->id]): '';
            }
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    /**
     * Get question of quiz
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @return \Illuminate\Http\JsonResponse
     * */
    public function getQuestionQuiz($quiz_id, $part_id, $attempt_id) {
        $user_id = Quiz::getUserId();
        $user_type = Quiz::getUserType();
        $attempt = QuizAttempts::where('id', '=', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->firstOrFail();

        $quiz = Quiz::findOrFail($quiz_id);
        $attempt_template = QuizAttemptsTemplate::where('attempt_id', '=', $attempt->id)->firstOrFail();
        $template = QuizTemplate::findOrFail($attempt_template->template_id);

        $check_query = QuizQuestionAnswerSelected::where('template_id', '=', $template->id)->first();

        $query = $check_query ? QuizQuestionAnswerSelected::query() : QuizTemplateQuestion::query();
        $query->where('template_id', '=', $template->id);
        $paginate = $query->paginate($quiz->questions_perpage);

        $rows = $query->get([
            'id',
            'template_id',
            'name',
            'type',
            'multiple',
            'max_score',
            'qindex',
            'question_id',
            'matching',
            'answer',
            'text_essay',
            'file_essay'
        ]);

        $next = false;
        if ($paginate->nextPageUrl()) {
            $next = true;
        }

        foreach ($rows as $row) {
            $question = Question::find($row->question_id);
            $answers = QuestionAnswer::where('question_id', '=', $row->question_id)->get(['id', 'title', 'correct_answer', 'feedback_answer', 'matching_answer', 'percent_answer']);

            $row->matching = ($row->matching) ? json_decode($row->matching) : '';
            $row->selected = ($row->answer) ? $row->answer : '';
            $row->text_essay = ($row->text_essay) ? json_decode(strip_tags(trim(html_entity_decode($row->text_essay,ENT_QUOTES,'UTF-8'), "\xc2\xa0")), true) : '';
            $row->answers = empty($answers) ? [] : $answers;
            $row->question = empty($question) ? [] : $question;
            $row->feedback_ques = ($question && $question->feedback) ? json_decode(strip_tags(trim(html_entity_decode($question->feedback,ENT_QUOTES,'UTF-8'), "\xc2\xa0")), true) : '';
            $row->answer_matching = QuestionAnswer::where('question_id', '=', $row->question_id)
                ->pluck('matching_answer')
                ->toArray();
        }

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
                'message' => trans('lageneral.successful_save')
            ]);
        }
        $user_type = Quiz::getUserType();
        $user_id = Quiz::getUserId();

        $qids = (array) $request->input('q', []);
        $attempt = QuizAttempts::where('id', '=', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->firstOrFail();
        $attempt_template = \DB::table('el_quiz_attempts_template')
            ->where('attempt_id', '=', $attempt->id)
            ->first();

        if (empty($attempt_template)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy template lần thi.',
            ]);
        }

        $questions = QuizQuestionAnswerSelected::getQuestionInArray($attempt_template->template_id, $qids);

        foreach ($questions as $ques_key => $question){
            $anwsers = $request->post('q_' . $question->id);
            $matching = $request->post('matching_' . $question->id);

            if ($question->type == 'essay'){
                $question->text_essay = !is_null($anwsers[0]) ? json_encode($anwsers) : '';
            }elseif ($question->type == 'matching'){
                $question->matching = count($matching)>0 ? json_encode($matching) : null;
            } else{
                $question->answer = !is_null($anwsers[0]) ? json_encode($anwsers) : null;
            }
            $question->save();

            QuizQuestionAnswerSelected::updateCoreQuestion($question->id);
        }

        return response()->json([
            'status' => 'success',
            'message' =>trans('lageneral.successful_save')
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
        $ques_id = $request->input('question_id');
        $file = $request->file('file_path');

        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $filename = $file->getClientOriginalName();

        if($storage->putFileAs('quiz', $file, $filename))
        {
            $question = QuizQuestionAnswerSelected::find($ques_id);
            $question->file_essay = $filename;
            $question->save();
        }

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
        $user_type = Quiz::getUserType();
        $user_id = Quiz::getUserId();
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

        $attempt_template = \DB::table('el_quiz_attempts_template')
            ->where('attempt_id', '=', $attempt->id)
            ->first();

        QuizAttempts::updateGradeAttempt($attempt->id);
        QuizTemplate::updateGradeQuiz($quiz_id, $user_id, $user_type);

        $select = QuizQuestionAnswerSelected::selectRaw('template_id,question_id, qindex, name,type,category_id,qqcategory_id,score_group,multiple,max_score,answer,text_essay,matching,score,created_at,updated_at,file_essay')->where('template_id', '=', $attempt_template->template_id);
        \DB::table('el_quiz_template_question')->insertUsing(['template_id', 'question_id', 'qindex', 'name', 'type', 'category_id', 'qqcategory_id', 'score_group', 'multiple', 'max_score', 'answer','text_essay', 'matching', 'score', 'created_at', 'updated_at', 'file_essay'], $select);
        QuizQuestionAnswerSelected::where('template_id', '=', $attempt_template->template_id)->delete();

        QuizAttempts::where('id', '=', $attempt->id)
            ->update(['timefinish' => time(), 'state' => 'completed']);

        $quizResult =tap(QuizResult::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type))
            ->update(['timecompleted' => time()])->first();
        $this->updateSendEmailResultQuiz($quiz_id,$user_id,$user_type,$quizResult->id);
        return response()->json([
            'status' => 'success',
            'message' => 'Đã nộp bài thi thành công',
            'redirect' => route('module.quiz.doquiz.index', [
                'quiz_id' => $quiz_id,
                'part_id' => $part_id,
            ]),
        ]);
    }
    public function updateSendEmailResultQuiz($quiz_id,$user_id,$user_type,$quiz_result_id)
    {
        $quiz = Quiz::with('type')->find($quiz_id);
        if ($quiz->quiz_type==1)
            return;
        $quizPartUsers = QuizRegister::with('quizparts:id,name,start_date,end_date')->where(['user_id'=>$user_id,'quiz_id'=>$quiz_id,'type'=>$user_type])->get()->pluck('quizparts')->flatten();
        $quiz_result = QuizResult::where(['quiz_id'=>$quiz_id,'user_id'=>$user_id,'type'=>$user_type])->first();
        if ($user_type)
            $user = Profile::where('user_id',$user_id)->first();
        else
            $user = QuizUserSecondary::find($user_id);
        foreach ($quizPartUsers as $quizPartUser) {
            $signature = getMailSignature($user_id, $user_type);
            $params = [
                'signature' => $signature,
                'gender' => $user_type==1?( $user->gender=='1'?'Anh':'Chị'):'Anh/Chị',
                'full_name' => $user_type==1?$user->full_name:$user->name,
                'quiz_name' => $quiz->name,
                'quiz_type' => $quiz->type?$quiz->type->name:'',
                'quiz_part_name' => $quizPartUser->name,
                'start_quiz_part' => $quizPartUser->start_date,
                'end_quiz_part' => $quizPartUser->end_date,
                'quiz_time' => $quiz->limit_time,
                'quiz_result' => $quiz_result->grade
            ];
            $user_id = [$user_id];
            $this->saveEmailQuizRegister($params,$user_id,$quiz_result_id);
        }
    }
    public function saveEmailQuizRegister(array $params,array $user_id,int $quiz_result_id)
    {
        $automail = new Automail();
        $automail->template_code = 'quiz_result';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $quiz_result_id;
        $automail->object_type = 'quiz_result';
        $automail->addToAutomail();
    }
}
