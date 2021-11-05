<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Permission;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\QuestionCategoryUser;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Exports\QuestionExport;
use Modules\Quiz\Imports\QuestionImport;
use App\Profile;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Font;

class QuestionlibController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();
        return view('quiz::backend.questionlib.index', [
            'categories' => $categories,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function question($category_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $errors = session()->get('errors');
        \Session::forget('errors');

        $category = QuestionCategory::findOrFail($category_id);
        return view('quiz::backend.questionlib.question', [
            'category' => $category,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function cateUser($category_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $category = QuestionCategory::findOrFail($category_id);
        $users = Profile::where('user_id', '>', 2)->get();
        $units = Unit::where('status', '=', 1)->get();
        return view('quiz::backend.questionlib.cate_user', [
            'category' => $category,
            'users' => $users,
            'units' => $units,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function questionForm($category_id, $question_id = null) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $category = QuestionCategory::findOrFail($category_id);
        $model = Question::firstOrNew(['id' => $question_id]);
        $answers = QuestionAnswer::where('question_id', '=', $model->id)->get();

        $feedbacks = json_decode($model->feedback,true);

        $page_title = strip_tags(substr(trim(html_entity_decode($model->name,ENT_QUOTES,'UTF-8'), "\xc2\xa0"), 0, 100));
        return view('quiz::backend.questionlib.question_form', [
            'category' => $category,
            'model' => $model,
            'answers' => $answers,
            'page_title' => if_empty($page_title, trans('backend.add_new')),
            'feedbacks' => $feedbacks,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function showModal(Request $request) {
        $model = QuestionCategory::firstOrNew(['id' => $request->id]);
        //if (Permission::isAdmin() || \permission('module.quiz.questionlib')) {
        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();
        // }
        // else {
        //     $profile = Profile::find(\Auth::id());
        //     //$managers = Permission::getIdUnitManagerByUser('module.training_unit');
        //     //$ids_unit = QuestionCategory::getCategoryUnit($managers);
        //     $ids_user = QuestionCategoryUser::getCategoryByUser($profile->code);
        //     //$ids = array_merge($ids_user, $ids_unit);
        //     $ids = $ids_user;
        //     $categories = QuestionCategory::getCategories(null, $ids, $request->id);
        // }
        return view('quiz::backend.modal.addqcat', [
            'model' => $model,
            'categories' => $categories
        ]);
    }

    public function getDataCategory(Request $request) {
        $search = $request->input('search');
        $parent_id = $request->input('parent_id');
        $user_id = \Auth::id();
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        QuestionCategory::addGlobalScope(new DraftScope());
        $query = QuestionCategory::query();
        /*$query->select([
            'a.id',
            'a.name',
            'a.parent_id',
            'a.status',
        ])
            ->from('el_question_category AS a');*/
            /*->leftJoin('el_question_category AS b', 'b.id', '=', 'a.parent_id');*/

        /*if (Permission::isUnitManager()) {
            $profile = Profile::find(\Auth::id());
            //$ids_unit = QuestionCategory::getCategoryUnit($managers);
            $ids_user = QuestionCategoryUser::getCategoryByUser($profile->code);
            //$ids = array_merge($ids_user, $ids_unit);
            $ids = $ids_user;
            $query->whereIn('id', $ids);
        }*/

        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhereIn('id', function ($subquery2) use ($search){
                    $subquery2->select(['category_id'])
                        ->from('el_question')
                        ->where('name', 'like', '%'. $search .'%');
                });
            });
        }

        if ($parent_id) {
            $query->where('parent_id', '=', $parent_id);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $parent = QuestionCategory::find($row->parent_id);
            $row->parent_name = $parent ? $parent->name : '';

            $row->cate_user_url = route('module.quiz.questionlib.cate_user', ['id' => $row->id]);
            $row->question_url = route('module.quiz.questionlib.question', ['id' => $row->id]);

            $num_question_approved = QuestionCategory::countQuestion($row->id);
            $num_question = Question::where('category_id', '=', $row->id)->count();

            $row->quantity = $num_question_approved .'/'. $num_question;

            $row->export_word = route('module.quiz.questionlib.export_word_question', ['id' => $row->id]);
            $row->export_excel = route('module.quiz.questionlib.export_excel_question', ['id' => $row->id]);

            $row->num_child = QuestionCategory::query()->where('parent_id', '=', $row->id)->count();
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveCategory(Request $request) {
        $this->validateRequest([
            'id' => 'nullable|exists:el_question_category,id',
            'name' => 'required',
            'parent_id' => 'nullable|exists:el_question_category,id',
        ], $request, QuestionCategory::getAttributeName());

        $model = QuestionCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if (empty($request->id)) {
            $model->created_by = Auth::id();
        }
        $model->updated_by = Auth::id();

        if ($model->save()) {
            if (!Permission::isAdmin()) {
                $user = Profile::find(Auth::id());
                $unit = Unit::where('code', '=', $user->unit_code)->first();

                $query = new QuestionCategoryUser();
                $query->category_id = $model->id;
                $query->unit_id = $unit->id;
                $query->save();
            }

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
            if (QuizQuestion::query()->where('qcategory_id', $id)->exists()){
                continue;
            }else{
                Question::where('category_id', $id)->delete();
                QuestionCategory::where('id', $id)->delete();
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveStatusCategory(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = QuestionCategory::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = QuestionCategory::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' =>trans('lageneral.successful_save')
        ]);
    }

    public function getDataQuestion($category_id, Request $request) {
        $search = $request->input('search');
        $type = $request->input('type');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        Question::addGlobalScope(new DraftScope());
        $query = Question::query();
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
            $row->edit_url = route('module.quiz.questionlib.question.edit', ['id' => $category_id, 'qid' => $row->id]);
            $row->view_question = route('module.quiz.questionlib.view_question', ['id' => $category_id, 'qid' => $row->id]);

            if ($row->type == 'essay'){
                $row->text_type = 'Tự luận';
            }elseif ($row->type == 'matching'){
                $row->text_type = 'Nối câu';
            }elseif ($row->type == 'fill_in'){
                $row->text_type = 'Điền vào chỗ trống';
            }elseif ($row->type == 'fill_in_correct'){
                $row->text_type = 'Điền từ chính xác';
            }else{
                $row->text_type = 'Trắc nghiệm ' . ($row->multiple == 1 ? '(Chọn nhiều)' : '(Chọn một)');
            }

            $row->answers = QuestionAnswer::whereQuestionId($row->id)->get();
            foreach ($row->answers as $key => $answer){
                $row->answers[$key]['image_answer'] = $answer->image_answer ? image_file($answer->image_answer) : "";
            }

            $row->created_by = Profile::fullname($row->updated_by);
            $row->created_time = 'tạo lúc '. get_date($row->created_at, 'd/m/Y h:i');

            $row->approved_by = $row->approved_by ? Profile::fullname($row->approved_by) : 'Chưa duyệt';
            $row->time_approved = $row->time_approved ? ('duyệt lúc '. get_date($row->time_approved, 'd/m/Y h:i')) : '';
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
        $feedbacks = $request->feedback;
        $feedback_answer = $request->feedback_answer;
        $matching_answer = $request->matching_answer;
        $percent_answer = $request->percent_answer;
        $image_answer = $request->image_answer;
        $fill_in_correct_answer = $request->fill_in_correct_answer;

        if ($percent_answer && $request->multiple == 1){
            $total = 0;
            foreach ($percent_answer as $item){
                $total += $item;
            }
            if ($total > 100){
                json_message('Tổng % đáp án không thể vượt quá 100%', 'error');
            }
            if ($total < 100){
                json_message('Tổng % đáp án không đủ 100%', 'error');
            }
        }
        if ($type == "matching") {
            if (count(array_filter($answer)) != count(array_filter($matching_answer))) {
                json_message('Đáp án nối câu chưa đủ', 'error');
            }
        }

        if ($type == "fill_in_correct") {
            if (count(array_filter($answer)) != count(array_filter($fill_in_correct_answer, function($val) { return ($val || is_numeric($val));}))) {
                json_message('Đáp án diên từ chính xác chưa đủ', 'error');
            }
        }

        if($type == "multiple-choise" && empty($answer)){
            json_message('Vui lòng nhập câu hỏi và đáp án', 'error');
        }

        if($type == "multiple-choise" && $request->multiple == 0 && array_count_values($correct_answer)[1] > 1){
            json_message('Câu hỏi chọn 1 không thể có nhiều đáp án đúng', 'error');
        }

        /*if($type == 'essay' && is_null($feedbacks)){
            json_message('Vui lòng nhập đáp án câu tự luận', 'error');
        }*/
        $arr = [];
        if ($feedbacks){
            foreach ($feedbacks as $feedback) {
                $arr[] = $feedback;
            }
        }

        $name = $request->name;

        $model = Question::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        // if($type == "multiple-choise") {
        //     $model->shuffle_answers = 1;
        // }
        $model->name = html_entity_decode($request->name);
        $model->category_id = $category_id;
        if (empty($request->id)) {
            $model->created_by = Auth::id();
        }
        $model->updated_by = Auth::id();
        $model->feedback = $type == 'essay' ? json_encode($arr) : '';
        $model->status = 2;
        $model->save();

        if($answer){
            foreach($answer as $ans_key => $ans){
                $answers = QuestionAnswer::firstOrNew(['id' => $ans_id[$ans_key]]);
                $answers->question_id = $model->id;
                if(isset($ans)){
                    $answers->title = html_entity_decode($ans);
                    $answers->correct_answer = $correct_answer[$ans_key] ? $correct_answer[$ans_key] : 0;
                    $answers->feedback_answer = $feedback_answer[$ans_key];
                    $answers->matching_answer = $matching_answer[$ans_key];
                    $answers->fill_in_correct_answer = $fill_in_correct_answer[$ans_key];
                    $answers->percent_answer = $percent_answer[$ans_key] ? $percent_answer[$ans_key] : 0;
                    $answers->save();
                }
            }
        }

        if($image_answer){
            foreach($image_answer as $ans_key => $ans){
                $answers = QuestionAnswer::firstOrNew(['id' => $ans_id[$ans_key]]);
                $answers->question_id = $model->id;

                if(isset($ans)){
                    $filename = $ans->getClientOriginalName();
                    $extension = $ans->getClientOriginalExtension();
                    $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) . '-' . time() . '-' . Str::random(10) . '.' . $extension;
                    $storage = \Storage::disk('upload');
                    $new_paths = $storage->putFileAs(date('Y/m/d'), $ans, $new_filename);

                    $answers->image_answer = $new_paths;
                    $answers->correct_answer = $correct_answer[$ans_key] ? $correct_answer[$ans_key] : 0;
                    $answers->feedback_answer = $feedback_answer[$ans_key];
                    $answers->matching_answer = $matching_answer[$ans_key];
                    $answers->fill_in_correct_answer = $fill_in_correct_answer[$ans_key];
                    $answers->percent_answer = $percent_answer[$ans_key] ? $percent_answer[$ans_key] : 0;
                    $answers->save();
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.quiz.questionlib.question', ['id' => $category_id]),
        ]);
    }

    public function removeQuestion(Request $request) {
        $this->validateRequest([
            'ids' => 'required'
        ], $request);

        $ids = $request->ids;
        foreach ($ids as $id) {
            if (QuizQuestion::query()->where('question_id', $id)->exists()){
                continue;
            }else{
                Question::where('id', $id)->delete();
            }
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

        QuestionAnswer::where('id', '=', $ans_id)->delete();

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
            $model = Question::findOrFail($id);
            $model->status = $status;
            $model->approved_by = Auth::id();
            $model->time_approved = date('Y-m-d h:i:s');
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }

    public function saveCateUser($category_id, Request $request) {
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
        ], $request, [
            'unit_id' => 'Đơn vị',
        ]);

        $unit_id = $request->unit_id;
        // $parent_id = $request->input('parent_id');
        // if ($parent_id && is_null($unit_id)){
        //     if(QuestionCategoryUser::checkExists($category_id, $parent_id)){

        //     }else{
        //         $model = new QuestionCategoryUser();
        //         $model->category_id = $category_id;
        //         $model->unit_id = $parent_id;
        //         $model->save();
        //     }

        //     json_result([
        //         'status' => 'success',
        //         'message' => trans('lageneral.successful_save')
        //     ]);
        // }
        if ($unit_id) {
            foreach ($unit_id as $item) {
                if (QuestionCategoryUser::checkExists($category_id, $item)) {
                    continue;
                }
                $model = new QuestionCategoryUser();
                $model->category_id = $category_id;
                $model->unit_id = $item;
                $model->save();
            }

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save')
            ]);
        }
    }

    public function getCateUser($category_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuestionCategoryUser::query();
        $query->select([
            'a.*',
            'b.code AS unit_code',
            'b.name AS unit_name'
        ]);
        $query->from('el_question_category_user AS a');
        $query->join('el_unit AS b', 'b.id', '=', 'a.unit_id');
        $query->where('a.category_id', '=', $category_id);

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

    public function removeCateUser($category_id, Request $request) {
        $ids = $request->input('ids', null);
        QuestionCategoryUser::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importQuestion($category_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new QuestionImport($category_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.quiz.questionlib.question', ['id' => $category_id]),
        ]);
    }

    public function exportWordQuestion($category_id) {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText(Str::upper('Danh sách câu hỏi'), [
            'name'=>'Times New Roman',
            'size' => 12,
            'bold' => true,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);

        $arrawser = range('a', 'z');

        $query = Question::query()
            ->where('category_id', '=', $category_id);
        $rows = $query->get([
            'id',
            'name',
            'type',
            'status'
        ]);

        foreach ($rows as $qindex => $row) {
            $status = ($row->status == '1') ? '(Đã duyệt)' : '(Chưa duyệt)';

            $text = trim(htmlspecialchars(strip_tags($row->name)), "\xc2\xa0");
            $textlines = explode("\n", $text);

            for ($i = 0; $i < sizeof($textlines); $i++) {
                $text = str_replace("\r", "", $textlines[$i]);
                if ($text != '') {
                    $section->addText($i == 0 ? (($qindex + 1).'. '. $text .' '. $status) : ($text .' '. $status), [
                        'name'=>'Times New Roman',
                        'size' => 12,
                    ]);
                }

            }

            if ($row->type == 'essay') {
                $section->addText(str_repeat('-', 675));
            }
            else {
                $answers = QuestionAnswer::query()->where('question_id', '=', $row->id)->get(['title', 'matching_answer', 'correct_answer', 'percent_answer']);

                foreach ($answers as $index => $answer) {
                    $val = str_repeat(' ', 5). $arrawser[$index] .'. '. htmlspecialchars($answer->title).' '.htmlspecialchars($answer->matching_answer);

                    if ($answer->correct_answer == 1 || $answer->percent_answer > 0) {
                        $section->addText($val, [
                            'name'=>'Times New Roman',
                            'size' => 12,
                            'underline' => Font::UNDERLINE_SINGLE,
                        ]);
                    }else{
                        $section->addText($val, [
                            'name'=>'Times New Roman',
                            'size' => 12,
                        ]);
                    }
                }
            }

        }

        $section->addText( '-- Hết --', [
            'name'=>'Times New Roman',
            'size' => 12,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $file_name = Str::slug('danhsachcauhoi');
        header("Content-Disposition: attachment; filename=". $file_name .".docx");
        $objWriter->save("php://output");
    }

    public function exportExcelQuestion($category_id){
        return (new QuestionExport($category_id))->download('danh_sach_cau_hoi.xlsx');
    }

    public function viewQuestion($category_id, $question_id){
        $category = QuestionCategory::findOrFail($category_id);
        $question = Question::findOrFail($question_id);
        $answers = QuestionAnswer::where('question_id', '=', $question_id)->get();

        return view('quiz::backend.questionlib.view_question', [
            'category' => $category,
            'question' => $question,
            'answers' => $answers
        ]);
    }
}
