<?php

namespace Modules\Survey\Http\Controllers;

use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Modules\Survey\Entities\SurveyAnswerMatrix;
use Modules\Survey\Entities\SurveyAnswerMatrix2;
use Modules\Survey\Entities\SurveyQuestion2;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyQuestionAnswer2;
use Modules\Survey\Entities\SurveyQuestionCategory;
use Modules\Survey\Entities\SurveyQuestionCategory2;
use Modules\Survey\Entities\SurveyTemplate2;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyObject;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Imports\ProfileImport;

class BackendController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('survey::backend.survey.index',[
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
        Survey::addGlobalScope(new DraftScope());
        $query = Survey::query();
        $query->select(['*']);
        $query->from('el_survey');

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $template = SurveyTemplate::find($row->template_id);

            $row->count_ques = SurveyQuestion::whereIn('category_id', function ($subquery) use ($template){
                $subquery->select(['id']);
                $subquery->from('el_survey_template_question_category');
                $subquery->where('template_id', '=', $template->id);
            })->count();

            $row->count_object = Profile::leftJoin('el_titles AS b', 'b.code', '=', 'title_code')
                ->leftJoin('el_unit AS c', 'c.code', '=', 'unit_code')
                ->whereIn('user_id', function ($subquery) use ($row){
                    $subquery->select(['user_id']);
                    $subquery->from('el_survey_object');
                    $subquery->where('survey_id', '=', $row->id);
                })
                ->orWhereIn('b.id', function ($subquery) use ($row){
                    $subquery->select(['title_id']);
                    $subquery->from('el_survey_object');
                    $subquery->where('survey_id', '=', $row->id);
                })
                ->orWhereIn('c.id', function ($subquery) use ($row){
                    $subquery->select(['unit_id']);
                    $subquery->from('el_survey_object');
                    $subquery->where('survey_id', '=', $row->id);
                })->count();

            $row->count_survey_user = SurveyUser::where('survey_id', '=', $row->id)->where('send', '=', 1)->count();
            $row->report_url = route('module.survey.report.export', ['survey_id' => $row->id]);
            $row->report_detail_url = route('module.survey.report.index', ['survey_id' => $row->id]);
            $row->edit_url = route('module.survey.edit', ['id' => $row->id]);
            $row->date = get_date($row->start_date, 'H:i d/m/Y') . ' <i class="fa fa-long-arrow-right"></i> ' . get_date($row->end_date, 'H:i d/m/Y');

            $row->review = route('module.survey.review_template', [$row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_hour' => 'required',
            'start_min' => 'required',
            'template_id' => 'required|exists:el_survey_template,id',
            'more_suggestions' => 'required',
            'custom_template' => 'nullable',
            'image' => 'nullable|string',
        ], $request, Survey::getAttributeName());

        $start_time = $request->input('start_hour') . ':' . $request->input('start_min') . ':00';
        $end_time = $request->input('end_hour') . ':' . $request->input('end_min') . ':00';

        $start_date = date_convert($request->input('start_date'), $start_time);
        $end_date = $request->input('end_date') ? date_convert($request->input('end_date'), $end_time) : null;

        $model = Survey::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->created_by = \Auth::id();
        $model->updated_by = \Auth::id();
        $model->start_date = $start_date;
        $model->end_date = $end_date;

        if ($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image);
        }

        $model->custom_template  = '';

        if ($request->input('end_date')){
            if($model->start_date >= $model->end_date){
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian kết thúc phải sau Thời gian bắt đầu',
                ]);
            }
        }

        if (empty($request->id)){
            if($model->start_date < date('Y-m-d')){
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian khảo sát tính từ ngày hiện tại',
                ]);
            }
        }

        if ($model->save()) {

            $template2 = SurveyTemplate2::whereSurveyId($model->id);
            if (!$template2->exists()){
                $template = SurveyTemplate::find($model->template_id)->toArray();

                $new_template = new SurveyTemplate2();
                $new_template->fill($template);
                $new_template->id = $template['id'];
                $new_template->survey_id = $model->id;
                $new_template->save();

                $categories = SurveyQuestionCategory::query()->where('template_id', $template['id'])->get()->toArray();
                foreach ($categories as $category){
                    $new_category = new SurveyQuestionCategory2();
                    $new_category->fill($category);
                    $new_category->id = $category['id'];
                    $new_category->survey_id = $model->id;
                    $new_category->save();

                    $questions = SurveyQuestion::query()->where('category_id', $category['id'])->get()->toArray();
                    foreach ($questions as $question){
                        $new_question = new SurveyQuestion2();
                        $new_question->fill($question);
                        $new_question->id = $question['id'];
                        $new_question->survey_id = $model->id;
                        $new_question->save();

                        $answers = SurveyQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                        foreach ($answers as $answer){
                            $new_answer = new SurveyQuestionAnswer2();
                            $new_answer->fill($answer);
                            $new_answer->id = $answer['id'];
                            $new_answer->survey_id = $model->id;
                            $new_answer->save();
                        }

                        $answers_matrix = SurveyAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                        foreach ($answers_matrix as $answer_matrix){
                            $new_answer_matrix = new SurveyAnswerMatrix2();
                            $new_answer_matrix->fill($answer_matrix);
                            $new_answer_matrix->survey_id = $model->id;
                            $new_answer_matrix->save();
                        }
                    }
                }
            }

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.survey.edit', ['id' => $model->id]),
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $errors = session()->get('errors');
        \Session::forget('errors');
        $survey_templates = SurveyTemplate::get();

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $title = Titles::get();
        $corporations = Unit::where('level', '=', 1)->where('status', '=', 1)->get();
        if ($id) {
            $model = Survey::find($id);
            $page_title = $model->name;

            $surver_user = SurveyUser::whereSurveyId($model->id)->first();

            return view('survey::backend.survey.form', [
                'model' => $model,
                'page_title' => $page_title,
                'survey_templates' => $survey_templates,
                'max_unit' => $max_unit,
                'level_name' => $level_name,
                'title' => $title,
                'corporations' => $corporations,
                'surver_user' => $surver_user,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }

        $model =  new Survey();
        $page_title = trans('backend.add_new') ;

        return view('survey::backend.survey.form', [
            'model' => $model,
            'page_title' =>$page_title,
            'survey_templates' => $survey_templates,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'title' => $title,
            'corporations' => $corporations,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $survey_user = SurveyUser::query()->where('survey_id', '=', $id);
            if ($survey_user->exists()){
                continue;
            }

            Survey::find($id)->delete();
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveObject($survey_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
            'parent_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'nullable',
        ], $request);

        $title_id = explode(',', $request->input('title_id'));
        $unit_id = $request->input('unit_id');
        $parent_id = $request->input('parent_id');

        if ($parent_id && is_null($unit_id)){
            if (SurveyObject::checkObjectUnit($survey_id, $parent_id)){

            }else{
                $model = new SurveyObject();
                $model->survey_id = $survey_id;
                $model->unit_id = $parent_id;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm đơn vị thành công',
            ]);
        }
        if ($unit_id) {
            foreach ($unit_id as $item){
                if (SurveyObject::checkObjectUnit($survey_id, $item)){
                    continue;
                }
                $model = new SurveyObject();
                $model->survey_id = $survey_id;
                $model->unit_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm đơn vị thành công',
            ]);
        }else{
            foreach ($title_id as $item){
                if (SurveyObject::checkObjectTitle($survey_id, $item)){
                    continue;
                }
                $model = new SurveyObject();
                $model->survey_id = $survey_id;
                $model->title_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm chức danh thành công',
            ]);
        }
    }

    public function getUserObject($survey_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SurveyObject::query();
        $query->select([
            'a.*',
            'b.code AS profile_code',
            'b.lastname',
            'b.firstname',
            'b.email',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.name AS parent_name'
        ]);
        $query->from('el_survey_object AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'd.parent_code');
        $query->where('a.survey_id', '=', $survey_id);
        $query->where('a.title_id', '=', null);
        $query->where('a.unit_id', '=', null);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->profile_name = $row->lastname . ' ' . $row->firstname;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getObject($survey_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SurveyObject::query();
        $query->select(['a.*', 'b.name AS title_name', 'c.name AS unit_name', 'd.name AS parent_name']);
        $query->from('el_survey_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('a.survey_id', '=', $survey_id);
        $query->where('a.user_id', '=', null);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeObject($survey_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Đối tượng',
        ]);

        $item = $request->input('ids');
        SurveyObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importObject($survey_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ProfileImport($survey_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.survey.edit', ['id' => $survey_id]),
        ]);
    }

    public function ajaxIsopenPublish(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Thông báo',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach($ids as $id){
            $model = Survey::findOrFail($id);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }

    public function getChild($survey_id, Request $request){
        $unit_id = $request->id;
        $unit = Unit::find($unit_id);

        $childs = Unit::where('parent_code', '=', $unit->code)->get(['id', 'name', 'code']);

        $count_child = [];
        $page_child = [];
        foreach ($childs as $item){
            $count_child[$item->id] = Unit::countChild($item->code);
            $page_child[$item->id] = route('module.survey.get_tree_child', ['id' => $survey_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }

    public function getTreeChild($survey_id, Request $request){
        $parent_code = $request->parent_code;
        return view('survey::backend.survey.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    public function reviewTemplate($id){
        $item = Survey::findOrFail($id);
        $template = SurveyTemplate::find($item->template_id);

        return view('survey::modal.review_template', [
            'item' => $item,
            'template' => $template,
        ]);
    }
}
