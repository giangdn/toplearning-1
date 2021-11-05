<?php

namespace Modules\TrainingUnit\Http\Controllers;

use App\Permission;
use App\Profile;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\TrainingUnit\Entities\ProposedQuestionCategoryLib;
use Modules\TrainingUnit\Imports\ProposedQuestionImport;
use Modules\TrainingUnit\Entities\ProposedQuestion;
use Modules\TrainingUnit\Entities\ProposedQuestionAnswer;
use Modules\TrainingUnit\Entities\ProposedQuestionCategory;

class ProposedQuestionController extends Controller
{
    public function index() {

        $categories = ProposedQuestionCategory::getCategories();

        return view('trainingunit::backend.questionlib.index', [
            'categories' => $categories
        ]);
    }

    public function question($category_id) {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $category = ProposedQuestionCategory::findOrFail($category_id);
        return view('trainingunit::backend.questionlib.question', [
            'category' => $category
        ]);
    }

    public function questionForm($category_id, $question_id = null) {
        $category = ProposedQuestionCategory::findOrFail($category_id);
        $model = ProposedQuestion::firstOrNew(['id' => $question_id]);
        $answers = ProposedQuestionAnswer::where('question_id', '=', $model->id)->get();

        return view('trainingunit::backend.questionlib.question_form', [
            'category' => $category,
            'model' => $model,
            'answers' => $answers,
            'page_title' => if_empty($model->name, trans('backend.add_new'))
        ]);
    }

    public function showModal(Request $request) {
        $model = ProposedQuestionCategory::firstOrNew(['id' => $request->id]);
        $categories = ProposedQuestionCategory::getCategories(null, [], $request->id);
        return view('trainingunit::backend.modal.addqcat', [
            'model' => $model,
            'categories' => $categories
        ]);
    }

    public function getDataCategory(Request $request) {
        $search = $request->input('search');
        $parent_id = $request->input('parent_id');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = ProposedQuestionCategory::query();
        $query->select(['a.*', 'b.name AS parent_name'])
            ->from('el_proposed_question_category AS a')
            ->leftJoin('el_proposed_question_category AS b', 'b.id', '=', 'a.parent_id');

        if ($search) {
            $query->where('a.name', 'like', '%'. $search .'%');
        }

        if ($parent_id) {
            $query->where('a.parent_id', '=', $parent_id);
        }

        if (!Permission::isAdmin()) {
            $ids = ProposedQuestionCategory::getCategoryByUser();
            $query->whereIn('a.id', $ids);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $profile = Profile::find($row->created_by);
            $row->question_url = route('module.training_unit.questionlib.question', ['id' => $row->id]);
            $row->quantity = ProposedQuestionCategory::countQuestion($row->id);
            $row->created_by2 = $profile->lastname . ' ' . $profile->firstname;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveCategory(Request $request) {
        $this->validateRequest([
            'id' => 'nullable|exists:el_proposed_question_category,id',
            'name' => 'required',
            'parent_id' => 'nullable|exists:el_proposed_question_category,id',
        ], $request, ProposedQuestionCategory::getAttributeName());

        $profile = Profile::find(Auth::id());
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        if (empty($unit)) {
            json_message('Không có đơn vị', 'error');
        }

        $model = ProposedQuestionCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if (empty($request->id)) {
            $model->created_by = Auth::id();
        }
        $model->updated_by = Auth::id();
        $model->unit_id = $unit->id;

        if ($model->save()) {
            json_message(trans('lageneral.successful_save'));
        }

        json_message('Không thể lưu dữ liệu', 'error');
    }

    public function removeCategory(Request $request) {
        $this->validateRequest([
            'ids' => 'required'
        ], $request);

        $ids = $request->ids;
        foreach ($ids as $id) {
            ProposedQuestion::where('category_id', $id)->delete();
            ProposedQuestionCategory::where('id', $id)->delete();
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function getDataQuestion($category_id, Request $request) {
        $search = $request->input('search');
        $type = $request->input('type');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = ProposedQuestion::query();
        $query->where('category_id', '=', $category_id);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        if ($type) {
            $query->where('type', '=', $type);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.training_unit.questionlib.question.edit', ['id' => $category_id, 'qid' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveQuestion($category_id, Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'type' => 'required_if:id,',
        ], $request, [
            'name' => 'Tên câu hỏi',
            'type' => 'Loại',
        ]);

        $type = $request->type;
        $answer = $request->answer;
        $correct_answer = $request->correct_answer;
        $ans_id = $request->ans_id;

        if($type == "multiple-choise" && empty($answer)){
            json_message('Vui lòng nhập câu hỏi và đáp án', 'error');
        }

        $model = ProposedQuestion::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $model->category_id = $category_id;
        if (empty($request->id)) {
            $model->created_by = Auth::id();
        }
        $model->updated_by = Auth::id();
        $model->status = 2;
        $model->save();

        if($answer){
            foreach($answer as $ans_key => $ans){
                $answers = ProposedQuestionAnswer::firstOrNew(['id' => $ans_id[$ans_key]]);
                $answers->question_id = $model->id;
                if(isset($ans)){
                    $answers->title = $ans;
                    $answers->correct_answer = $correct_answer[$ans_key];
                    $answers->save();
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.training_unit.questionlib.question', ['id' => $category_id]),
        ]);
    }

    public function removeQuestion(Request $request) {
        $this->validateRequest([
            'ids' => 'required'
        ], $request);

        $ids = $request->ids;
        foreach ($ids as $id) {
            ProposedQuestion::where('id', $id)->delete();
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function removeQuestionAnswer(Request $request) {
        $this->validateRequest([
            'ans_id' => 'required'
        ], $request);

        $ans_id = $request->ans_id;

        ProposedQuestionAnswer::where('id', '=', $ans_id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveStatus(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Kỳ thi',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach($ids as $id){
            $model = ProposedQuestion::findOrFail($id);
            if ($model->status == 1) {
                json_result([
                    'status' => 'error',
                    'message' => 'Trạng thái không được thay đổi tiếp',
                ]);
            }
            $model->status = $status;
            $model->save();

            if ($model->status == 1){
                $proposed_cate_ques = ProposedQuestionCategory::find($model->category_id);

                $proposed_ques_cate_lib = ProposedQuestionCategoryLib::where('pqc_id', '=', $proposed_cate_ques->id)->first();

                if ($proposed_ques_cate_lib){

                    $question = new Question();
                    $question->name = $model->name;
                    $question->type = $model->type;
                    $question->category_id = $proposed_ques_cate_lib->qcl_id;
                    $question->multiple = $model->multiple;
                    $question->status = $model->status;
                    $question->created_by = $model->created_by;
                    $question->updated_by = $model->updated_by;
                    $question->save();

                }else{

                    $category_question = new QuestionCategory();
                    $category_question->name = $proposed_cate_ques->name;
                    $category_question->parent_id = $proposed_cate_ques->parent_id;
                    $category_question->status = $proposed_cate_ques->status;
                    $category_question->unit_id = $proposed_cate_ques->unit_id;
                    $category_question->created_by = $proposed_cate_ques->created_by;
                    $category_question->updated_by = $proposed_cate_ques->updated_by;
                    $category_question->save();

                    $proposed_ques_cate_lib = new ProposedQuestionCategoryLib();
                    $proposed_ques_cate_lib->pqc_id = $proposed_cate_ques->id;
                    $proposed_ques_cate_lib->qcl_id = $category_question->id;
                    $proposed_ques_cate_lib->save();

                    $question = new Question();
                    $question->name = $model->name;
                    $question->type = $model->type;
                    $question->category_id = $proposed_ques_cate_lib->qcl_id;
                    $question->multiple = $model->multiple;
                    $question->status = $model->status;
                    $question->created_by = $model->created_by;
                    $question->updated_by = $model->updated_by;
                    $question->save();
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }

    public function importQuestion($category_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ProposedQuestionImport($category_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.training_unit.questionlib.question', ['id' => $category_id]),
        ]);
    }

}
