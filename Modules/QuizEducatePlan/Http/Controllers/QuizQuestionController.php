<?php

namespace Modules\QuizEducatePlan\Http\Controllers;

use App\Permission;
use App\Profile;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\QuestionCategoryUser;
use Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion;
use Modules\QuizEducatePlan\Entities\QuizEducatePlan;
use Modules\Quiz\Entities\Question;
use Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory;
use Modules\Quiz\Entities\QuizTemplateQuestionRand;
use TorMorten\Eventy\Facades\Events as Eventy;

class QuizQuestionController extends Controller
{
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

        return view('quiz::backend.quiz.question', [
            'quiz_questions' => $quiz_questions,
            'categories' => $categories,
            'questions' => $questions,
            'quiz' => $quiz,
            'qqc' => $qqc,
            'unit' => $unit,
            'disabled' => $disabled
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

    public function showModal($idsg, $id, Request $request) {
        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();
        $count_question = function ($cat_id) {
            return QuestionCategory::countQuestion($cat_id);
        };
        return view('quizeducateplan::backend.modal_question_category', [
            'categories' => $categories,
            'quiz_id' => $id,
            'count_question' => $count_question,
            'idsg' => $idsg
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

            $quiz = Quiz::find($id);
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
            /****************update qqcategory_id quiz_template_rand***/
            /*QuizTemplateQuestionRand::updateqqcategoryidQuesion($id);*/

            json_message('Lưu đề mục thành công');
        }

        json_message('Lỗi không thể thêm đề mục', 'error');
    }

    public function removeQQCategory($id, Request $request) {
        QuizQuestionCategory::destroy([$request->category_id]);

        $quiz = Quiz::find($id);
        $quiz->status = 2;
        $quiz->save();

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

        $quiz = Quiz::find($id);
        $quiz->status = 2;
        $quiz->save();

        $redirect = /*$quiz->unit_id > 0 ? route('module.training_unit.quiz.question', ['id' => $id]) :*/ route('module.quiz.question', ['id' => $id]) ;
        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => $redirect
        ]);
    }

    public function saveCategoryQuestion($idsg, $id, Request $request) {
        $this->validateRequest([
            'ids' => 'nullable|exists:el_question,id',
        ], $request);
        $cate_id = $request->cate_id;
        $ids = $request->ids;
        $max_order = QuizEducatePlanQuestion::getMaxOrder($id);
        $qindex = $max_order;
        $question_id =[]; $quiz_question_id=[];
        foreach($ids as $ques_id){
            if(QuizEducatePlanQuestion::where('quiz_id', '=', $id)->where('question_id', '=', $ques_id)->exists()){
                continue;
            }

            $max_order += 1;
            $model = new QuizEducatePlanQuestion();
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
        $quiz = QuizEducatePlan::find($id);
        $quiz->status = 2;
        $quiz->save();

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.quiz_plan.question', ['idsg' => $idsg, 'id' => $id])
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

        $quiz = Quiz::find($quiz_id);
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

        $quiz_question = QuizQuestion::find($id);
        $quiz_question->max_score = $max_score;
        $quiz_question->save();

       /* QuizTemplateQuestionRand::where('quiz_question_id','=',$id)->where('quiz_id','=',$quiz_id)->update(['max_score'=>$max_score]);*/

        $quiz = Quiz::find($quiz_id);
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

        $quiz = Quiz::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();

        json_message('ok');
    }
}
