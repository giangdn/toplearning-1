<?php

namespace Modules\Survey\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyAnswerMatrix;
use Modules\Survey\Entities\SurveyQuestionCategory;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyUser;

class TemplateController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('survey::backend.template.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SurveyTemplate::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.survey.template.edit', ['id' => $row->id]);
            $row->created_by = Profile::fullname($row->created_by) .' ('. Profile::usercode($row->created_by) .')';
            $row->updated_by = Profile::fullname($row->updated_by) .' ('. Profile::usercode($row->updated_by) .')';

            $row->review = route('module.survey.template.review', [$row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        if ($id) {
            $model = SurveyTemplate::find($id);
            $page_title = $model->name;
            $categories = SurveyQuestionCategory::where('template_id', '=', $model->id)->get();

            return view('survey::backend.template.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }

        $model = new SurveyTemplate();
        $page_title = trans('backend.add_new') ;

        return view('survey::backend.template.form', [
            'model' => $model,
            'page_title' => $page_title,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'category_name' => 'required',
            'question_name' => 'required',
            'type' => 'required',
        ], $request, [
            'name' => 'Tên mẫu',
            'category_name' => 'Danh mục',
            'question_name' => 'Câu hỏi',
            'type' => 'Loại câu hỏi'
        ]);

        $category_id = $request->category_id;
        $category_name = $request->category_name;
        $question_id = $request->question_id;
        $question_code = $request->question_code;
        $question_name = $request->question_name;
        $answer_id = $request->answer_id;
        $answer_code = $request->answer_code;
        $answer_name = $request->answer_name;
        $is_text = $request->is_text;
        $is_row = $request->is_row;
        $type = $request->type;
        $multiple = $request->multiple;

        $answer_matrix_code = $request->answer_matrix_code;

        $model = SurveyTemplate::firstOrNew(['id' => $request->id]);
        $model->name = $request->name;
        $model->save();

        foreach($category_name as $cate_key => $cate_name) {
            $category = SurveyQuestionCategory::firstOrNew(['id' => $category_id[$cate_key]]);
            $category->template_id = $model->id;
            $category->name = trim($cate_name);
            $category->save();

            foreach ($question_name[$cate_key] as $ques_key => $ques_name) {
                $question = SurveyQuestion::firstOrNew(['id' => $question_id[$cate_key][$ques_key]]);
                $question->category_id = $category->id;
                $question->code = isset($question_code[$cate_key][$ques_key]) ? $question_code[$cate_key][$ques_key] : null;
                $question->name = $ques_name;
                $question->type = $type[$cate_key][$ques_key];
                $question->multiple = isset($multiple[$cate_key][$ques_key]) ? $multiple[$cate_key][$ques_key] : 0;
                $question->save();

                if(isset($answer_name[$cate_key][$ques_key])){
                    foreach($answer_name[$cate_key][$ques_key] as $ans_key => $ans_name){
                        $answer = SurveyQuestionAnswer::firstOrNew(['id' => $answer_id[$cate_key][$ques_key][$ans_key]]);
                        $answer->question_id = $question->id;
                        $answer->code = isset($answer_code[$cate_key][$ques_key][$ans_key]) ? $answer_code[$cate_key][$ques_key][$ans_key] : null;
                        $answer->name = $ans_name;
                        $answer->is_text = $question->type == 'matrix_text' ? 1 : (isset($is_text[$cate_key][$ques_key][$ans_key]) ? $is_text[$cate_key][$ques_key][$ans_key] : 0);
                        $answer->is_row = isset($is_row[$cate_key][$ques_key][$ans_key]) ? $is_row[$cate_key][$ques_key][$ans_key] : 0;
                        $answer->save();
                    }
                }

                if (($question->type == 'matrix' && $question->multiple == 1) || $question->type == 'matrix_text'){
                    $rows = SurveyQuestionAnswer::whereQuestionId($question->id)->where('is_row', '=', 1)->pluck('id')->toArray();
                    $cols = SurveyQuestionAnswer::whereQuestionId($question->id)->where('is_row', '=', 0)->pluck('id')->toArray();

                    if(isset($answer_matrix_code[$cate_key][$ques_key])) {
                        foreach ($answer_matrix_code[$cate_key][$ques_key] as $ans_key => $answer_matrix) {
                            foreach ($answer_matrix as $matrix_key => $matrix_code){
                                SurveyAnswerMatrix::query()
                                    ->updateOrCreate([
                                        'question_id' => $question->id,
                                        'answer_row_id' => $rows[$ans_key],
                                        'answer_col_id' => $cols[$matrix_key]
                                    ],[
                                        'code' => $matrix_code
                                    ]);
                            }
                        }
                    }
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.survey.template')
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        foreach($ids as $id){
            $survey = SurveyUser::where('template_id', '=', $id)->get();
            foreach($survey as $item){
                if($item->send == 1){
                    json_message('Mẫu ' . $id . ' không thể xóa', 'error');
                }
            }

            $del_categories = SurveyQuestionCategory::getCategoryTemplate($id);
            foreach($del_categories as $cate_id){
                SurveyQuestionAnswer::whereIn('question_id', function ($sub) use ($cate_id){
                    $sub->select(['id'])
                        ->from('el_survey_template_question')
                        ->where('category_id', '=', $cate_id)
                        ->pluck('id')->toArray();
                })->delete();
                SurveyAnswerMatrix::whereIn('question_id', function ($sub) use ($cate_id){
                    $sub->select(['id'])
                        ->from('el_survey_template_question')
                        ->where('category_id', '=', $cate_id)
                        ->pluck('id')->toArray();
                })->delete();
                SurveyQuestion::whereCategoryId($cate_id)->delete();
                SurveyQuestionCategory::whereId($cate_id)->delete();
            }
        }
        SurveyTemplate::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function removeCategory(Request $request) {
        $cate_id = $request->input('cate_id', null);

        SurveyQuestionAnswer::whereIn('question_id', function ($sub) use ($cate_id){
            $sub->select(['id'])
                ->from('el_survey_template_question')
                ->where('category_id', '=', $cate_id)
                ->pluck('id')->toArray();
        })->delete();
        SurveyAnswerMatrix::whereIn('question_id', function ($sub) use ($cate_id){
            $sub->select(['id'])
                ->from('el_survey_template_question')
                ->where('category_id', '=', $cate_id)
                ->pluck('id')->toArray();
        })->delete();
        SurveyQuestion::whereCategoryId($cate_id)->delete();
        SurveyQuestionCategory::whereId($cate_id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function removeQuestion(Request $request) {
        $ques_id = $request->input('ques_id', null);

        SurveyQuestionAnswer::whereQuestionId($ques_id)->delete();
        SurveyAnswerMatrix::where('question_id', '=', $ques_id)->delete();
        SurveyQuestion::whereId($ques_id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function removeAnswer(Request $request) {
        $ans_id = $request->input('ans_id', null);

        $answer = SurveyQuestionAnswer::whereId($ans_id)->first();

        if ($answer->is_row == 1){
            SurveyAnswerMatrix::query()
                ->where('question_id', '=', $answer->question_id)
                ->where('answer_row_id', '=', $answer->id)
                ->delete();
        }else{
            SurveyAnswerMatrix::query()
                ->where('question_id', '=', $answer->question_id)
                ->where('answer_col_id', '=', $answer->id)
                ->delete();
        }

        $answer->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function reviewTemplate($id){
        $template = SurveyTemplate::find($id);

        $type = 1;
        return view('survey::modal.review_template', [
            'template' => $template,
            'type' => $type
        ]);
    }
}
