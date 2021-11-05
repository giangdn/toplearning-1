<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Permission;
use App\Profile;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\QuestionCategoryUser;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizTemplateQuestionRand;
use Modules\Quiz\Entities\ReportCorrectAnswerRate;
use TorMorten\Eventy\Facades\Events as Eventy;

class QuizQuestionController extends Controller
{
    protected $template = [];
    protected $ramdom_questions = [0];
    protected $answer_text = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'p',
        'q',
    ];

    public function index($quiz_id) {
        $profile = Profile::find(\Auth::id());
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz = Quiz::find($quiz_id);
        $quiz_questions = QuizQuestion::getQuestions($quiz_id);
        $categories = function($cat_id){
            return QuestionCategory::find($cat_id);
        };
        $questions = function($ques_id){
            return Question::find($ques_id);
        };
        $qqc = function ($quiz_id, $num_order) {
            return QuizQuestionCategory::where('quiz_id', '=', $quiz_id)
                ->where('num_order', '=', $num_order)
                ->orderBy('num_order', 'ASC')
                ->get();
        };

        $result = QuizResult::where('quiz_id', '=', $quiz_id)
            ->first();
        if ($result){
            $disabled = 'disabled';
        }else{
            $disabled = '';
        }

        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('quiz::backend.quiz.question', [
            'quiz_questions' => $quiz_questions,
            'categories' => $categories,
            'questions' => $questions,
            'quiz' => $quiz,
            'qqc' => $qqc,
            'unit' => $unit,
            'disabled' => $disabled,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function showModalCategory($id, Request $request) {
        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();
        $count_question = function ($cat_id) {
            return QuestionCategory::countQuestion($cat_id);
        };
        $quiz_id = $id;
        return view('quiz::backend.modal.question_random', [
            'categories' => $categories,
            'quiz_id' => $quiz_id,
            'count_question' => $count_question,
        ]);
    }

    public function showModal($id, Request $request) {
        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();
        $count_question = function ($cat_id) {
            return QuestionCategory::countQuestion($cat_id);
        };
        return view('quiz::backend.modal.question_category', [
            'categories' => $categories,
            'quiz_id' => $id,
            'count_question' => $count_question,
        ]);
    }

    public function showModalQQCategory($id, Request $request) {
        $quiz = Quiz::find($id);
        $num_order = $request->num_order;
        $category = QuizQuestionCategory::firstOrNew(['id' => $request->category_id]);
        return view('quiz::backend.modal.add_qqcategory', [
            'quiz' => $quiz,
            'num_order' => $num_order,
            'category' => $category
        ]);
    }

    public function saveQQCategory($id, Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'num_order' => 'required',
            'percent_group' => 'required',
        ], $request, ['name' => 'Tên đề mục']);

        $num_order = $request->num_order;
        $percent_group = $request->percent_group;

        $model = QuizQuestionCategory::firstOrNew(['id' => $request->id]);
        if (empty($request->id)) {
            $model->quiz_id = $id;
            $model->num_order = $num_order - 1;
        }
        $total = QuizQuestionCategory::sumMaxScoreByQuizID($id);

        if ($request->id){
            if ($percent_group >= $model->percent_group){
                if ($total == 100){
                    json_message('Tổng phần trăm không thể lưu được nữa', 'error');
                }

                if (($total + ($percent_group - $model->percent_group)) > 100){
                    json_message('Tổng phần trăm chỉ còn ' . (100 - $total) , 'error');
                }
            }
        }else{
            if ($total == 100){
                json_message('Tổng phần trăm không thể lưu được nữa', 'error');
            }

            if (($total + $percent_group) > 100){
                json_message('Tổng phần trăm chỉ còn ' . (100 - $total) , 'error');
            }

        }

        $model->name = $request->name;
        $model->percent_group = $percent_group;

        if ($model->save()) {
            $questions = QuizQuestion::where('quiz_id','=', $id)
                ->orderBy('num_order', 'ASC')
                ->get();

            $categories = QuizQuestionCategory::where('quiz_id','=', $id)
                ->orderBy('num_order', 'ASC')
                ->get();

            /*$quiz = Quiz::find($id);
            $quiz->status = 2;
            $quiz->save();*/

            foreach ($categories as $category){
                foreach ($questions as $key => $question){
                    if ($question->num_order >= ($category->num_order + 1)){
                        $question->qqcategory = $category->id;
                        $question->save();
                    }
                }
            }
            /****************update qqcategory_id quiz_template_rand***/
            /*QuizTemplateQuestionRand::updateqqcategoryidQuesion($id);*/

            json_message('Lưu đề mục thành công');
        }

        json_message('Lỗi không thể thêm đề mục', 'error');
    }

    public function removeQQCategory($id, Request $request) {
        QuizQuestionCategory::destroy([$request->category_id]);

        /*$quiz = Quiz::find($id);
        $quiz->status = 2;
        $quiz->save();*/

        json_message('ok');
    }

    public function saveQuestionRandom($id, Request $request) {
        $this->validateRequest([
            'category_id' => 'nullable|exists:el_question_category,id',
            'random_question' => 'required|numeric',
        ], $request, QuizQuestion::getAttributeName());

        $random_question = $request->random_question;
        $cat_id = $request->category_id;
        $count_question = QuestionCategory::countQuestion($cat_id);
        $total_question_random = QuizQuestion::countQuestion($id,$cat_id);

        $rest = $count_question - $total_question_random;

        if($random_question > $count_question){
            json_message('Số câu hỏi ngẫu nhiên vượt quá số câu hỏi trong danh mục', 'error');
        }

        if($random_question > $rest){
            json_message('Danh mục chỉ còn thêm được '.$rest.' câu hỏi', 'error');
        }

        $max_order = QuizQuestion::getMaxOrder($id);
        $qindex = $max_order;

        for($ii = 1; $ii <= $random_question; $ii++){
            $max_order += 1;
            $model = new QuizQuestion();
            $model->quiz_id = $id;
            $model->qcategory_id = $cat_id;
            $model->random = 1;
            $model->num_order = $max_order;
            $model->save();
            $quiz_question_id[] = $model->id;
        }

        /****************/
        /*for ($i = 1; $i <= 10; $i++){
            $qindex_tmp = $qindex;
            $collection_question = QuizTemplateQuestionRand::generateTemplateQuestionRandom($id,$i,$cat_id,$random_question)->toArray();
            $data_insert=[];
            for($o = 0; $o < $random_question; $o++) {
                $qindex_tmp +=1;
                $random = array_rand($collection_question);
                $data_insert[] =
                    [
                        'template_id' => $i,
                        'quiz_id' => $id,
                        'question_id' => $collection_question[$random],
                        'quiz_question_id' => $quiz_question_id[$o],
                        'qindex' => $qindex_tmp,
                        'category_id' => $cat_id,
                    ]
                ;
                unset($collection_question[$random]);
            }
            QuizTemplateQuestionRand::insert($data_insert);
        }*/
        /****************/

        /*$quiz = Quiz::find($id);
        $quiz->status = 2;
        $quiz->save();*/

        $redirect = /*$quiz->unit_id > 0 ? route('module.training_unit.quiz.question', ['id' => $id]) :*/ route('module.quiz.question', ['id' => $id]) ;
        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => $redirect
        ]);
    }

    public function saveCategoryQuestion($id, Request $request) {
        $this->validateRequest([
            'ids' => 'nullable|exists:el_question,id',
        ], $request);
        $cate_id = $request->cate_id;
        $ids = $request->ids;
        $max_order = QuizQuestion::getMaxOrder($id);
        $qindex = $max_order;
        $question_id =[]; $quiz_question_id=[];
        foreach($ids as $ques_id){
            if(QuizQuestion::where('quiz_id', '=', $id)->where('question_id', '=', $ques_id)->exists()){
                continue;
            }

            $max_order += 1;
            $model = new QuizQuestion();
            $model->quiz_id = $id;
            $model->question_id = $ques_id;
            $model->num_order = $max_order;
            $model->qcategory_id = $cate_id;
            $model->save();

            $question_id[] = $ques_id;
            $quiz_question_id[] = $model->id;
        }

        /******xóa câu random đã chọn****/

        /*QuizTemplateQuestionRand::removeAndRandom($id,$question_id);*/
        /****************/
        /*for ($i = 1; $i <= 10; $i++){
            $qindex_tmp = $qindex;
            $data_insert=[];
            $o = 0;
            foreach($ids as $ques_id) {
                $qindex_tmp += 1;
                $data_insert[] =
                    [
                        'template_id' => $i,
                        'quiz_id' => $id,
                        'question_id' => $ques_id,
                        'quiz_question_id' => $quiz_question_id[$o],
                        'qindex' => $qindex_tmp,
                        'category_id' => $cate_id,
                    ]
                ;
                $o++;
            }
            QuizTemplateQuestionRand::insert($data_insert);
        }*/
        /**********/
       /* $quiz = Quiz::find($id);
        $quiz->status = 2;
        $quiz->save();*/

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.quiz.question', ['id' => $id])
        ]);
    }

    public function getDataQuestion($quiz_id, Request $request) {
        $this->validateRequest([
            'category_id' => 'nullable|exists:el_question_category,id',
        ], $request);

        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $exclude_ids = QuizQuestion::getArrayQuestions($quiz_id);

        $query = Question::query();
        $query->where('category_id', '=', $request->category_id);
        $query->where('status', '=', 1);
        $query->whereNotIn('id', $exclude_ids);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeQuizQuestion($quiz_id, Request $request){
        $this->validateRequest([
            'quiz_ques_id' => 'required',
        ], $request);

        $id = $request->quiz_ques_id;
       /* QuizTemplateQuestionRand::where('quiz_id', '=', $quiz_id)->where('quiz_question_id', '=', $id)->delete();*/
        QuizQuestion::find($id)->delete();

       /* $quiz = Quiz::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();*/

        json_message('ok');
    }

    public function updateMaxScore($quiz_id, Request $request){
        $this->validateRequest([
            'quiz_ques_id' => 'required',
            'max_score' => 'required',
        ], $request);

        $id = $request->quiz_ques_id;
        $max_score = $request->max_score;

        $quiz_question = QuizQuestion::find($id);
        $quiz_question->max_score = $max_score;
        $quiz_question->save();

       /* QuizTemplateQuestionRand::where('quiz_question_id','=',$id)->where('quiz_id','=',$quiz_id)->update(['max_score'=>$max_score]);*/

       /* $quiz = Quiz::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();*/

        json_message('ok');
    }

    public function updateNumOrder($quiz_id, Request $request){
        $this->validateRequest([
            'question' => 'required',
        ], $request);

        $questions = $request->question;
        $category = 0;
        $index = 0;
        foreach ($questions as $question) {
            if (is_numeric($question)) {
                QuizQuestion::where('id', '=', $question)->update([
                    'num_order' => ($index + 1),
                    'qqcategory' => $category
                ]);

                $index ++;
            }
            else {
                $catid = str_replace('c_', '', $question);
                QuizQuestionCategory::where('id', '=', $catid)->update([
                    'num_order' => ($index)
                ]);
                $category = $catid;
            }
        }
        /**********update index quiz_question_template_rand***/
        /*QuizTemplateQuestionRand::updateIndexQuestion($quiz_id);*/

        /*$quiz = Quiz::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();*/

        json_message('ok');
    }

    public function reviewQuiz($quiz_id, Request $request){
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $quiz = Quiz::findOrFail($quiz_id);

        $this->create($quiz);

        $template = $this->getTemplateData($quiz);

        $questions = $template['questions'];
        $qqcategorys = $template['categories'];

        $qqcategory = [];
        foreach ($qqcategorys as $item) {
            $qqcategory['num_' . $item['num_order']] = $item['name'];
            $qqcategory['percent_' . $item['num_order']] = $item['percent_group'];
        }

        return view('quiz::backend.quiz.view', [
            'quiz' => $quiz,
            'questions' => $questions,
            'disabled' => 1,
            'qqcategory' => $qqcategory,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getQuestionReviewQuiz($quiz_id, Request $request) {
        $quiz = Quiz::findOrFail($quiz_id);

        $template = $this->getTemplateData($quiz);

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

        $data = ['rows' => $rows, 'next' => $next];
        return \response()->json($data);
    }

    protected function getTemplateData($quiz) {
        $storage = \Storage::disk('local');
        $template = 'review_quiz/quiz-' . $quiz->id .'.json';

        if ($storage->exists($template)) {
            return json_decode($storage->get($template), true);
        }
        return null;
    }

    protected function create($quiz) {
        $this->mapQuizQuestionCategories($quiz);
        $this->mapQuizQuestions($quiz);

        if ($this->template) {
            $storage = \Storage::disk('local');
            $attempt_folder = 'review_quiz';

            if (!$storage->exists($attempt_folder)) {
                \File::makeDirectory($storage->path($attempt_folder), 0777, true);
            }

            $quiz->save();
            $storage->put($attempt_folder . '/quiz-' . $quiz->id . '.json', json_encode($this->template));

            return true;
        }

        return false;
    }

    protected function mapQuizQuestionCategories($quiz) {
        $qqcategorys = QuizQuestionCategory::whereQuizId($quiz->id)->orderBy('num_order', 'asc')
            ->get();

        $categories = [];
        foreach ($qqcategorys as $qqcategory) {
            $max_score = $qqcategory->sumMaxScore();
            $per_score = $qqcategory->percent_group > 0 ? ($quiz->max_score * $qqcategory->percent_group / 100) / ($max_score ? $max_score : 1) : ($quiz->max_score / $max_score);

            $categories[] = [
                'name' => $qqcategory->name,
                'num_order' => $qqcategory->num_order,
                'percent_group' => $qqcategory->percent_group,
                'qqcategory' => $qqcategory->id,
                'max_score' => $max_score,
                'per_score' => $per_score,
            ];
        }

        $this->template['categories'] = $categories;
    }

    protected function mapQuizQuestions($quiz) {
        $questions = [];
        $max_score = QuizQuestion::getTotalScore($quiz->id);
        $score_group = $max_score > 0 ? ($quiz->max_score / $max_score) : 0;

        if ($quiz->shuffle_question == 1){
            $list_questions = $quiz->questions()->inRandomOrder()->get();
        }else{
            $list_questions = $quiz->questions;
        }

        foreach ($list_questions as $key => $question) {
            if ($question->random == 1) {
                $ranrom = Question::where('category_id','=', $question->qcategory_id)
                    ->whereNotIn('id', $this->ramdom_questions)
                    ->whereNotIn('id', function (Builder $builder) use ($quiz) {
                        $builder->select(['question_id'])
                            ->from('el_quiz_question')
                            ->where('quiz_id', '=', $quiz->id)
                            ->whereNotNull('question_id');
                    })
                    ->inRandomOrder()
                    ->first();

                $questions[$question->id] = [
                    'id' => $question->id,
                    'index' => $key,
                    'qindex' => $question->num_order,
                    'question_id' => $ranrom->id,
                    'name' => $ranrom->name,
                    'type' => $ranrom->type,
                    'category_id' => $ranrom->category_id,
                    'qqcategory_id' => $question->qqcategory,
                    'multiple' => $ranrom->multiple,
                    'score_group' => $score_group,
                    'max_score' => $question->max_score,
                    'answers' => $this->getAnwsersQuestion($quiz, $ranrom->id),
                    'correct_answers' => $this->getCorrectAnwsersQuestion($ranrom),
                    'answer_horizontal' => $ranrom->answer_horizontal,
                ];

                $this->ramdom_questions[] = $ranrom->id;
            }
            else {
                $questions[$question->id] = [
                    'id' => $question->id,
                    'index' => $key,
                    'qindex' => $question->num_order,
                    'question_id' => $question->question_id,
                    'name' => $question->question->name,
                    'type' => $question->question->type,
                    'category_id' => $question->question->category_id,
                    'qqcategory_id' => $question->qqcategory,
                    'multiple' => $question->question->multiple,
                    'score_group' => $score_group,
                    'max_score' => $question->max_score,
                    'answers' => $this->getAnwsersQuestion($quiz, $question->question_id),
                    'correct_answers' => $this->getCorrectAnwsersQuestion($question->question),
                    'answer_horizontal' => $question->question->answer_horizontal,
                ];
            }
        }

        $this->template['questions'] = $questions;
    }

    protected function getAnwsersQuestion($quiz, $question_id) {
        $question = Question::find($question_id);
        $anwsers = QuestionAnswer::whereQuestionId($question_id);
        if ($quiz->shuffle_answers == 1 && $question->shuffle_answers == 1){
            $anwsers->inRandomOrder();
        }
        $anwsers = $anwsers->get([
            'id',
            'title',
            'feedback_answer',
            'matching_answer',
            'percent_answer',
            'image_answer',
            'fill_in_correct_answer',
        ])->toArray();

        foreach ($anwsers as $index => $anwser) {
            $anwsers[$index]['index_text'] = @$this->answer_text[$index];
            $anwsers[$index]['image_answer'] = $anwser['image_answer'] ? image_file($anwser['image_answer']) : '';
        }

        return $anwsers;
    }

    protected function getCorrectAnwsersQuestion($question){
        $correct_answers = [];
        if ($question->type == 'multiple-choise') {
            if ($question->multiple == 0){
                $correct_answers = QuestionAnswer::where('question_id', '=', $question->id)
                    ->where('correct_answer', '=', 1)
                    ->pluck('id')
                    ->toArray();
            }
            if ($question->multiple == 1){
                $correct_answers = QuestionAnswer::where('question_id', '=', $question->id)
                    ->where('percent_answer', '>', 0)
                    ->pluck('id')
                    ->toArray();
            }
        }
        if ($question->type == 'matching'){
            $correct_answers = [];
            $answers = QuestionAnswer::query()->where('question_id', '=', $question->id)->whereNotNull('matching_answer')->get();
            foreach ($answers as $answer){
                $correct_answers[] = $answer->id;
            }
        }

        return $correct_answers;
    }
}
