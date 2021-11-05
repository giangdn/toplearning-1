<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Profile;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\Question;

use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesQuestion;
use Modules\Quiz\Entities\QuizTemplatesQuestionCategory;
use TorMorten\Eventy\Facades\Events as Eventy;

class QuizTemplateQuestionController extends Controller
{
    public function index($quiz_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $profile = Profile::find(\Auth::id());
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz = QuizTemplates::find($quiz_id);
        $quiz_questions = QuizTemplatesQuestion::getQuestions($quiz_id);
        $categories = function($cat_id){
            return QuestionCategory::find($cat_id);
        };
        $questions = function($ques_id){
            return Question::find($ques_id);
        };
        $qqc = function ($quiz_id, $num_order) {
            return QuizTemplatesQuestionCategory::where('quiz_id', '=', $quiz_id)
                ->where('num_order', '=', $num_order)
                ->orderBy('num_order', 'ASC')
                ->get();
        };
        
        return view('quiz::backend.quiz_template.question', [
            'quiz_questions' => $quiz_questions,
            'categories' => $categories,
            'questions' => $questions,
            'quiz' => $quiz,
            'qqc' => $qqc,
            'unit' => $unit,
            'disabled' => '',
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
        return view('quiz::backend.modal.quiz_template_question_random', [
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
        return view('quiz::backend.modal.quiz_template_question_category', [
            'categories' => $categories,
            'quiz_id' => $id,
            'count_question' => $count_question,
        ]);
    }

    public function showModalQQCategory($id, Request $request) {
        $quiz = QuizTemplates::find($id);
        $num_order = $request->num_order;
        $category = QuizTemplatesQuestionCategory::firstOrNew(['id' => $request->category_id]);
        return view('quiz::backend.modal.quiz_template_add_qqcategory', [
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

        $model = QuizTemplatesQuestionCategory::firstOrNew(['id' => $request->id]);
        if (empty($request->id)) {
            $model->quiz_id = $id;
            $model->num_order = $num_order - 1;
        }
        $total = QuizTemplatesQuestionCategory::sumMaxScoreByQuizID($id);

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
            $questions = QuizTemplatesQuestion::where('quiz_id','=', $id)
                ->orderBy('num_order', 'ASC')
                ->get();

            $categories = QuizTemplatesQuestionCategory::where('quiz_id','=', $id)
                ->orderBy('num_order', 'ASC')
                ->get();

            $quiz = QuizTemplates::find($id);
            $quiz->status = 2;
            $quiz->save();

            foreach ($categories as $category){
                foreach ($questions as $key => $question){
                    if ($question->num_order >= ($category->num_order + 1)){
                        $question->qqcategory = $category->id;
                        $question->save();
                    }
                }
            }

            json_message('Lưu đề mục thành công');
        }

        json_message('Lỗi không thể thêm đề mục', 'error');
    }

    public function removeQQCategory($id, Request $request) {
        QuizTemplatesQuestionCategory::destroy([$request->category_id]);

        $quiz = QuizTemplates::find($id);
        $quiz->status = 2;
        $quiz->save();

        json_message('ok');
    }

    public function saveQuestionRandom($id, Request $request) {
        $this->validateRequest([
            'category_id' => 'nullable|exists:el_question_category,id',
            'random_question' => 'required|numeric',
        ], $request, QuizTemplatesQuestion::getAttributeName());

        $random_question = $request->random_question;
        $cat_id = $request->category_id;
        $count_question = QuestionCategory::countQuestion($cat_id);
        $total_question_random = QuizTemplatesQuestion::countQuestion($id,$cat_id);

        $rest = $count_question - $total_question_random;

        if($random_question > $count_question){
            json_message('Số câu hỏi ngẫu nhiên vượt quá số câu hỏi trong danh mục', 'error');
        }

        if($random_question > $rest){
            json_message('Danh mục chỉ còn thêm được '.$rest.' câu hỏi', 'error');
        }

        $max_order = QuizTemplatesQuestion::getMaxOrder($id);
        $qindex = $max_order;

        for($ii = 1; $ii <= $random_question; $ii++){
            $max_order += 1;
            $model = new QuizTemplatesQuestion();
            $model->quiz_id = $id;
            $model->qcategory_id = $cat_id;
            $model->random = 1;
            $model->num_order = $max_order;
            $model->save();
            $quiz_question_id[] = $model->id;
        }

        $quiz = QuizTemplates::find($id);
        $quiz->status = 2;
        $quiz->save();

        $redirect = route('module.quiz_template.question', ['id' => $id]) ;
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
        $max_order = QuizTemplatesQuestion::getMaxOrder($id);
        $qindex = $max_order;
        $question_id =[]; $quiz_question_id=[];
        foreach($ids as $ques_id){
            if(QuizTemplatesQuestion::where('quiz_id', '=', $id)->where('question_id', '=', $ques_id)->exists()){
                continue;
            }

            $max_order += 1;
            $model = new QuizTemplatesQuestion();
            $model->quiz_id = $id;
            $model->question_id = $ques_id;
            $model->num_order = $max_order;
            $model->qcategory_id = $cate_id;
            $model->save();

            $question_id[] = $ques_id;
            $quiz_question_id[] = $model->id;
        }

        $quiz = QuizTemplates::find($id);
        $quiz->status = 2;
        $quiz->save();

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.quiz_template.question', ['id' => $id])
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
        $exclude_ids = QuizTemplatesQuestion::getArrayQuestions($quiz_id);

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

        QuizTemplatesQuestion::find($id)->delete();

        $quiz = QuizTemplates::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();

        json_message('ok');
    }

    public function updateMaxScore($quiz_id, Request $request){
        $this->validateRequest([
            'quiz_ques_id' => 'required',
            'max_score' => 'required',
        ], $request);

        $id = $request->quiz_ques_id;
        $max_score = $request->max_score;

        $quiz_question = QuizTemplatesQuestion::find($id);
        $quiz_question->max_score = $max_score;
        $quiz_question->save();

        $quiz = QuizTemplates::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();

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
                QuizTemplatesQuestion::where('id', '=', $question)->update([
                    'num_order' => ($index + 1),
                    'qqcategory' => $category
                ]);

                $index ++;
            }
            else {
                $catid = str_replace('c_', '', $question);
                QuizTemplatesQuestionCategory::where('id', '=', $catid)->update([
                    'num_order' => ($index)
                ]);
                $category = $catid;
            }
        }

        $quiz = QuizTemplates::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();

        json_message('ok');
    }
}
