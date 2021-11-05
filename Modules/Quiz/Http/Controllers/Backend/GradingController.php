<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Automail;
use App\Permission;
use App\Models\Categories\TrainingTeacher;
use App\Profile;
use App\ProfileView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ModelHistory\Entities\ModelHistory;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizAttemptsTemplate;
use Modules\Quiz\Entities\QuizGraded;
use Modules\Quiz\Entities\QuizPermissionTeacher;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizTemplate;
use Modules\Quiz\Entities\QuizTemplateQuestion;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Entities\ReportCorrectAnswerRate;

class GradingController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);

        return view('quiz::backend.grading.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[5],
        ]);
    }

    public function user($quiz_id) {
        $teacher_id = 0;
        $teacher = TrainingTeacher::where('user_id', '=', \Auth::id())->first();
        if ($teacher) {
            $teacher_id = $teacher->id;
        }

        $query = Quiz::query();
        $query->where('id', '=', $quiz_id)
            ->where('status', '=', 1);

        if (!Permission::isAdmin()) {
            $query->whereIn('id', function ($subquery) use ($teacher_id) {
                $subquery->select(['quiz_id'])
                    ->from('el_quiz_teacher')
                    ->where('teacher_id', '=', $teacher_id);
            });
        }

        $quiz = $query->firstOrFail();
        return view('quiz::backend.grading.user', [
            'quiz' => $quiz
        ]);
    }

    public function grading($quiz_id, $type, $user_id) {
        $teacher_id = 0;
        $teacher = TrainingTeacher::where('user_id', '=', \Auth::id())->first();
        if ($teacher) {
            $teacher_id = $teacher->id;
        }

        $query = Quiz::query();
        $query->where('id', '=', $quiz_id)
            ->where('status', '=', 1);

        if (!Permission::isAdmin()) {
            $query->whereIn('id', function ($subquery) use ($teacher_id) {
                $subquery->select(['quiz_id'])
                    ->from('el_quiz_teacher')
                    ->where('teacher_id', '=', $teacher_id);
            });
        }
        $quiz = $query->firstOrFail();

        $attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->latest()
            ->firstOrFail();
        $template = $attempt->getTemplateData($quiz->id, $attempt->id);

        $questions = [];
        foreach ($template['questions'] as $key => $question){
            if (in_array($question['type'], ['fill_in', 'essay'])){
                $questions[] = $question;
            }
        }
        $qqcategorys = $template['categories'];

        $qqcategory = [];
        foreach ($qqcategorys as $item) {
            $qqcategory['num_' . $item['num_order']] = $item['name'];
            $qqcategory['percent_' . $item['num_order']] = $item['percent_group'];
        }

        $permission_teacher = QuizPermissionTeacher::query()
            ->where('quiz_id', '=', $quiz_id)
            ->where('teacher_id', '=', $teacher_id)
            ->first();
        $permission_teacher_question = [];
        if ($permission_teacher){
            $permission_teacher_question = explode(',', $permission_teacher->question_id);
        }

        return view('quiz::backend.grading.grading', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'user_id' => $user_id,
            'type' => $type,
            'questions' => $questions,
            'disabled' => 1,
            'qqcategory' => $qqcategory,
            'permission_teacher_question' => $permission_teacher_question,
        ]);
    }

    public function getDataQuiz(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Quiz::active()
            ->hasEndPart();

        if (!Auth::user()->isAdmin()) {
            $query->whereHas('teachers', function ($q) {
                $q->select(['id'])
                    ->where('teacher_id', '=', @Auth::user()->teacher->id);
            });
        }

        if ($search){
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->quantity = QuizRegister::where('quiz_id', '=', $row->id)->count();
            $row->quantity_quiz_attempts = QuizAttempts::where('quiz_id', '=', $row->id)->count();
            $row->edit_url = route('module.quiz.grading.user', [$row->id]);
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function getDataUser($quiz_id, Request $request) {
        $graded = $request->input('graded');
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizRegister::query();
        $query->select([
            'a.id',
            'a.type',
            'a.user_id',
            'b.code AS user_code',
            'b.lastname',
            'b.firstname',
            'b.email',
            'c.name AS secondary_name',
            'c.code AS secondary_code',
        ]);
        $query->from('el_quiz_register AS a');
        $query->leftJoin('el_profile AS b', function ($join) {
            $join->on('b.user_id', '=', 'a.user_id')
                ->where('a.type', '=', 1);
        });
        $query->leftJoin('el_quiz_user_secondary AS c', function ($join) {
            $join->on('c.id', '=', 'a.user_id')
                ->where('a.type', '=', 2);
        });

        $query->where('quiz_id', '=', $quiz_id);

        if ($search){
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('c.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('c.code', 'like', '%'. $search .'%');
            });
        }

        if ($graded == 1) {
            $query->whereExists(function ($sub) {
                $sub->select(\DB::raw(1))
                    ->from('el_quiz_graded')
                    ->whereColumn('quiz_id', '=', 'a.quiz_id')
                    ->whereColumn('user_id', '=', 'a.user_id')
                    ->whereColumn('user_type', '=', 'a.type');
            });
        }

        if ($graded == 2) {
            $query->whereNotExists(function ($sub) {
                $sub->select(\DB::raw(1))
                    ->from('el_quiz_graded')
                    ->whereColumn('quiz_id', '=', 'a.quiz_id')
                    ->whereColumn('user_id', '=', 'a.user_id')
                    ->whereColumn('user_type', '=', 'a.type');
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $graded = QuizGraded::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $row->user_id)
                ->where('user_type', '=', $row->type)
                ->first();

            $row->graded = ($graded ? 'Đã chấm' : 'Chưa chấm');

            $row->status = $this->getStatusUser($row->user_id, $quiz_id);
            $row->grading_url = route('module.quiz.grading.user.grading', [
                'quiz_id' => $quiz_id,
                'type' => $row->type,
                'user_id' => $row->user_id
            ]);
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function getStatusUser($user_id, $quiz_id) {
        $result = QuizResult::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('timecompleted', '>=', 0);
        if ($result->exists()) {
            return 1;
        }

        return 0;
    }

    public function getQuestion($quiz_id, $type, $user_id, Request $request) {
        $quiz = Quiz::findOrFail($quiz_id);
        $attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type);

        if (isset($request->attempt) && is_numeric($request->attempt)) {
            $attempt->where('id', '=', $request->attempt);
        }

        $attempt = $attempt->latest()->firstOrFail();

        $template = $attempt->getTemplateData($quiz->id, $attempt->id);

        $questions = [];
        foreach ($template['questions'] as $key => $question){
            if (in_array($question['type'], ['fill_in', 'essay'])){
                $questions[] = $question;
            }
        }

        $total = count($questions);
        $total_page = ceil( $total / $quiz->questions_perpage );

        $page = $request->get('page');
        $offset = ($page - 1) * $quiz->questions_perpage;
        if( $offset < 0 ) $offset = 0;

        $rows = array_slice($questions, $offset, $quiz->questions_perpage );

        $next = false;
        if ($page < $total_page) {
            $next = true;
        }

        return response()->json([
            'rows' => $rows,
            'next' => $next
        ]);
    }

    public function saveScore($quiz_id, $type, $user_id, Request $request) {
        $this->validateRequest([
            'score' => 'required',
            'question_id' => 'required',
        ], $request, ['score' => 'Điểm', 'question_id' => 'Câu hỏi']);

        $question_id = $request->question_id;
        $score = $request->score;

        $attempt = QuizAttempts::query()
            ->where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->latest()->firstOrFail();

        $template = $attempt->getTemplateData();
        $questions = $template['questions'];

        $question = $questions[$question_id];
        if ($score > $question['max_score']){
            return response()->json([
                'status' => 'error',
                'message' => 'Điểm không thể lớn hơn '. $question['max_score'],
            ]);
        }
        $question['score'] = $question['score_group'] * $score;

        $template['questions'][$question_id]['score'] = $question['score'];

        $attempt->updateTemplateData($template);

        // hist
        $student = ProfileView::find($user_id)->full_name;
        $quiz = Quiz::find($quiz_id)->name;
        $modelHist= new ModelHistory();
        $modelHist->model_id=$attempt->id;
        $modelHist->model ='el_quiz_update_attempt';
        $modelHist->code ='Update';
        $modelHist->action ='Cập nhật chấm điểm học viên '.$student;
        $modelHist->note = $quiz;
        $modelHist->parent_id = $quiz_id;
        $modelHist->parent_model = 'el_quiz';
        $modelHist->save();
        ////
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
        ]);
    }

    public function saveComment($quiz_id, $type, $user_id, Request $request) {
        $this->validateRequest([
            'score' => 'required',
            'question_id' => 'required',
        ], $request, [
            'score' => 'Đánh giá',
            'question_id' => 'Câu hỏi'
        ]);

        $question_id = $request->question_id;
        $score = $request->score;

        $attempt = QuizAttempts::query()
            ->where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->latest()->firstOrFail();

        $update_attempt = QuizUpdateAttempts::query()
            ->where('attempt_id', '=', $attempt->id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->first();

        $questions = json_decode($update_attempt['questions'], true);
        $template = $attempt->getTemplateData();

        $question = $questions[$question_id];
        $question['grading_comment'] = $score;
        $template['questions'][$question_id]['grading_comment'] = $score;

        $questions[$question_id] = $question;
        $update_attempt->update([
            'questions' => json_encode($questions),
        ]);

        $attempt->updateTemplateData($template);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
        ]);
    }

    public function gradeComplete($quiz_id, $type, $user_id, Request $request) {
        $this->validateRequest([
            'attempt' => 'required|exists:el_quiz_attempts,id',
        ], $request);

        \Artisan::call('attempt:complete '. $request->attempt);

        QuizTemplate::updateGradeQuiz($quiz_id, $user_id, $type);

        $graded = QuizGraded::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', $type)
            ->first();
        if (!$graded){
            $grading = new QuizGraded();
            $grading->quiz_id = $quiz_id;
            $grading->user_id = $user_id;
            $grading->user_type = $type;
            if($grading->save())
                $this->updateSendEmailResultQuiz($quiz_id,$user_id,$type);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
            'redirect' => route('module.quiz.grading.user', ['quiz_id' => $quiz_id]),
        ]);
    }
    public function updateSendEmailResultQuiz($quiz_id,$user_id,$user_type)
    {
        $quiz = Quiz::with('type')->find($quiz_id);
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
            $this->saveEmailQuizRegister($params,$user_id,$quiz_result->id);
        }
    }
    public function saveEmailQuizRegister(array $params,array $user_id,int $quiz_result_id)
    {
        $automail = new Automail();
        $automail->template_code = 'quiz_registerd';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $quiz_result_id;
        $automail->object_type = 'approve_quiz';
        $automail->addToAutomail();
    }
}
