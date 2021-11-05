<?php

namespace Modules\Rating\Http\Controllers;

use App\CourseRegisterView;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRatingLevel;
use Modules\Offline\Entities\OfflineRatingLevelObject;
use Modules\Offline\Entities\OfflineRatingLevelObjectColleague;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRatingLevel;
use Modules\Online\Entities\OnlineRatingLevelObject;
use Modules\Online\Entities\OnlineRatingLevelObjectColleague;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterView;
use Modules\Online\Entities\OnlineResult;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Rating\Entities\RatingAnswerMatrix;
use Modules\Rating\Entities\RatingCourseAnswer;
use Modules\Rating\Entities\RatingCourseCategory;
use Modules\Rating\Entities\RatingCourseQuestion;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\Rating\Entities\RatingLevelCourseCategory;
use Modules\Rating\Entities\RatingTemplate;
use Modules\Rating\Entities\RatingCategory;
use Modules\Rating\Entities\RatingQuestion;
use Modules\Rating\Entities\RatingQuestionAnswer;
use Modules\Rating\Entities\RatingCourse;
use Illuminate\Support\Facades\Auth;
use App\Profile;
use Modules\Rating\Exports\ReportExport;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Font;
use Barryvdh\DomPDF\Facade as PDF;

class BackendController extends Controller
{
    //Thêm mẫu đánh giá
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        // return view('rating::backend.template.index');
        return view('backend.evaluate_training_effectiveness.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => 'evaluate_training_effectiveness',
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        RatingTemplate::addGlobalScope(new DraftScope());
        $query = RatingTemplate::query();

        if ($search) {
            $query->where(function ($sub) use ($search){
                $sub->orWhere('code', 'like', '%'. $search .'%');
                $sub->orWhere('name', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.rating.template.edit', ['id' => $row->id]);
            $row->created_by = Profile::fullname($row->created_by) .' ('. Profile::usercode($row->created_by) .')';
            $row->updated_by = Profile::fullname($row->updated_by) .' ('. Profile::usercode($row->updated_by) .')';

            $row->time_created = get_date($row->created_at, 'H:i:s d/m/Y');
            $row->time_updated = get_date($row->updated_at, 'H:i:s d/m/Y');

            $row->export_word = route('module.rating.template.export_word', ['id' => $row->id]);
            $row->export_pdf = route('module.rating.template.export_pdf', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        if ($id) {
            $model = RatingTemplate::find($id);
            $page_title = $model->name;
            $categories = RatingCategory::where('template_id', '=', $model->id)->get();

            return view('rating::backend.template.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'get_menu_child' => $get_menu_child,
                'name_url' => 'evaluate_training_effectiveness',
            ]);
        }

        $model = new RatingTemplate();
        $page_title = trans('backend.add_new');

        return view('rating::backend.template.form', [
            'model' => $model,
            'page_title' => $page_title,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'evaluate_training_effectiveness',
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'category_name' => 'required',
            'question_name' => 'required',
            'type' => 'required',
        ], $request, [
            'category_name' => 'Danh mục',
            'question_name' => 'Câu hỏi',
            'question_answer_name' => 'Câu trả lời',
            'type' => 'Loại câu hỏi',
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

        if (count(array_filter($category_id, function($val) {return ($val || is_null($val));})) != count(array_filter($category_name))) {
            json_message('Đề mục chưa đủ', 'error');
        }

        $model = RatingTemplate::firstOrNew(['id' => $request->id]);
        $model->code = $request->code;
        $model->name = $request->name;
        $model->description = $request->description;
        $model->save();

        foreach($category_name as $cate_key => $cate_name) {
            $category = RatingCategory::firstOrNew(['id' => $category_id[$cate_key]]);
            $category->template_id = $model->id;
            $category->name = trim($cate_name);
            $category->save();

            foreach ($question_name[$cate_key] as $ques_key => $ques_name) {
                $question = RatingQuestion::firstOrNew(['id' => $question_id[$cate_key][$ques_key]]);
                $question->category_id = $category->id;
                $question->code = isset($question_code[$cate_key][$ques_key]) ? $question_code[$cate_key][$ques_key] : null;
                $question->name = $ques_name;
                $question->type = $type[$cate_key][$ques_key];
                $question->multiple = isset($multiple[$cate_key][$ques_key]) ? $multiple[$cate_key][$ques_key] : 0;
                $question->save();

                if(isset($answer_name[$cate_key][$ques_key])){
                    foreach($answer_name[$cate_key][$ques_key] as $ans_key => $ans_name){
                        $answer = RatingQuestionAnswer::firstOrNew(['id' => $answer_id[$cate_key][$ques_key][$ans_key]]);
                        $answer->question_id = $question->id;
                        $answer->code = isset($answer_code[$cate_key][$ques_key][$ans_key]) ? $answer_code[$cate_key][$ques_key][$ans_key] : null;
                        $answer->name = $ans_name;
                        $answer->is_text = $question->type == 'matrix_text' ? 1 : (isset($is_text[$cate_key][$ques_key][$ans_key]) ? $is_text[$cate_key][$ques_key][$ans_key] : 0);
                        $answer->is_row = isset($is_row[$cate_key][$ques_key][$ans_key]) ? $is_row[$cate_key][$ques_key][$ans_key] : 0;
                        $answer->save();
                    }
                }

                if (($question->type == 'matrix' && $question->multiple == 1) || $question->type == 'matrix_text'){
                    $rows = RatingQuestionAnswer::where('question_id', $question->id)->where('is_row', '=', 1)->pluck('id')->toArray();
                    $cols = RatingQuestionAnswer::where('question_id', $question->id)->where('is_row', '=', 0)->pluck('id')->toArray();

                    if(isset($answer_matrix_code[$cate_key][$ques_key])) {
                        foreach ($answer_matrix_code[$cate_key][$ques_key] as $ans_key => $answer_matrix) {
                            foreach ($answer_matrix as $matrix_key => $matrix_code){
                                RatingAnswerMatrix::query()
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
            'redirect' => route('module.rating.template')
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        foreach($ids as $id){
            $rating_course = RatingCourse::where('template_id', '=', $id)->get();
            foreach($rating_course as $item){
                if($item->send == 1){
                    json_message('Mẫu ' . $id . ' không thể xóa', 'error');
                }
            }

            $del_categories = RatingCategory::getCategoryTemplate($id);
            foreach($del_categories as $del_cate){
                $del_questions = RatingQuestion::getQuestion($del_cate->id);
                foreach ($del_questions as $del_ques) {
                    RatingQuestionAnswer::where('question_id', '=', $del_ques->id)->delete();
                    RatingAnswerMatrix::where('question_id', '=', $del_ques->id)->delete();
                }
                RatingQuestion::where('category_id', '=', $del_cate->id)->delete();
            }
            RatingCategory::where('template_id', '=', $id)->delete();
        }
        RatingTemplate::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function removeCategory(Request $request) {
        $cate_id = $request->input('cate_id', null);

        RatingQuestionAnswer::whereIn('question_id', function ($sub) use ($cate_id){
            $sub->select(['id'])
                ->from('el_rating_question')
                ->where('category_id', '=', $cate_id)
                ->pluck('id')->toArray();
        })->delete();
        RatingAnswerMatrix::whereIn('question_id', function ($sub) use ($cate_id){
            $sub->select(['id'])
                ->from('el_rating_question')
                ->where('category_id', '=', $cate_id)
                ->pluck('id')->toArray();
        })->delete();
        RatingQuestion::where('category_id', '=', $cate_id)->delete();
        RatingCategory::where('id', $cate_id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function removeQuestion(Request $request) {
        $ques_id = $request->input('ques_id', null);

        RatingQuestionAnswer::where('question_id', '=', $ques_id)->delete();
        RatingAnswerMatrix::where('question_id', '=', $ques_id)->delete();
        RatingQuestion::where('id', $ques_id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function removeAnswer(Request $request) {
        $ans_id = $request->input('ans_id', null);

        $answer = RatingQuestionAnswer::where('id', $ans_id)->first();
        if ($answer->is_row == 1){
            RatingAnswerMatrix::query()
                ->where('question_id', '=', $answer->question_id)
                ->where('answer_row_id', '=', $answer->id)
                ->delete();
        }else{
            RatingAnswerMatrix::query()
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

    public function copy(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $template = RatingTemplate::find($id);
            $newTemplate = $template->replicate();
            $newTemplate->code = $newTemplate->code . '_copy'. rand(2, 50);
            $newTemplate->save();

            $categories = RatingCategory::where('template_id', '=', $id)->get();
            foreach ($categories as $category){
                $newCategory = $category->replicate();
                $newCategory->template_id = $newTemplate->id;
                $newCategory->save();

                $questions = RatingQuestion::where('category_id', '=', $category->id)->get();
                foreach ($questions as $question){
                    $newQuestion = $question->replicate();
                    $newQuestion->category_id = $newCategory->id;
                    $newQuestion->save();

                    $answers = RatingQuestionAnswer::where('question_id', '=', $question->id)->get();
                    foreach ($answers as $answer){
                        $newAnswer = $answer->replicate();
                        $newAnswer->question_id = $newQuestion->id;
                        $newAnswer->save();
                    }
                }
            }
        }
    }

    public function exportWord($template_id) {
        $tenplate = RatingTemplate::find($template_id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText(Str::upper($tenplate->name), [
            'name'=>'Times New Roman',
            'size' => 14,
            'bold' => true,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);

        $section->addText(' ');

        $arrawser = range('a', 'z');

        $categories = RatingCategory::query()
            ->where('template_id', '=', $template_id)
            ->get();

        foreach ($categories as $cate_key => $category) {
            $section->addText(Str::upper($category->name), [
                'name'=>'Times New Roman',
                'size' => 12,
                'bold' => true,
            ]);

            $section->addText(' ');

            $questions = $category->questions;
            foreach ($questions as $ques_key => $question){
                switch ($question->type){
                    case 'choice' : $type = 'Trắc nghiệm'; break;
                    case 'essay' : $type = 'Tự luận'; break;
                    case 'text' : $type = 'Nhập text'; break;
                    case 'matrix' : $type = 'Ma trận'; break;
                    case 'matrix_text' : $type = 'Ma trận (nhập text)'; break;
                    case 'dropdown' : $type = 'Lựa chọn'; break;
                    case 'sort' : $type = 'Sắp xếp'; break;
                    case 'percent' : $type = 'Phần trăm'; break;
                    case 'number' : $type = 'Nhập số'; break;
                    case 'time' : $type = 'Thời gian'; break;
                }

                $section->addText(str_repeat(' ', 3) . ($ques_key + 1).'. '.$question->name .' ('. $type . ')', [
                    'name'=>'Times New Roman',
                    'size' => 12,
                ]);

                if ($question->type == 'essay') {
                    $section->addText(str_repeat('-', 675));
                }

                if ($question->type == 'time') {
                    $section->addText(str_repeat('-', 135));
                }

                $answers = $question->answers->where('is_row', '=', 1);
                $answers_col = $question->answers->where('is_row', '=', 0);
                if ($question->type == 'sort') {
                    foreach ($answers as $answer){
                        $section->addText(str_repeat(' ', 5) . str_repeat('-', 10) .' '. $answer->name, [
                            'name' => 'Times New Roman',
                            'size' => 12,
                        ]);
                    }
                }
                if ($question->type == 'choice' || $question->type == 'dropdown'){
                    foreach ($answers as $answer) {
                        $section->addText(str_repeat(' ', 5) . $answer->name, [
                            'name' => 'Times New Roman',
                            'size' => 12,
                        ]);
                    }
                }
                if (in_array($question->type, ['text', 'percent', 'number'])) {
                    foreach ($answers as $answer){
                        $section->addText(str_repeat(' ', 5) . $answer->name . str_repeat('-', 100));
                    }
                }
                if (in_array($question->type, ['matrix', 'matrix_text'])){
                    $styleTable = array('borderSize' => 6, 'borderColor' => '000', 'cellMargin' => 80);
                    $styleFirstRow = array('borderBottomSize' => 6, 'borderBottomColor' => '0000FF');
                    $styleCell = array('valign' => 'center', 'align' => 'center');
                    $fontStyle = array('bold' => true, 'align' => 'center', 'valign' => 'center',);
                    $phpWord->addTableStyle('Table', $styleTable, $styleFirstRow);
                    $table = $section->addTable('Table');

                    $table->addRow(500);
                    $table->addCell(1000, $styleCell)->addText('');
                    foreach ($answers_col as $answer){
                        $table->addCell(1000, $styleCell)->addText($answer->name, $fontStyle);
                    }

                    foreach ($answers as $answer) {
                        $table->addRow(500);
                        $table->addCell(1000)->addText($answer->name);

                        foreach ($answers_col as $key => $item) {
                            $table->addCell(1000);
                        }
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
        $file_name = Str::slug($tenplate->name);
        header("Content-Disposition: attachment; filename=". $file_name .".docx");
        $objWriter->save("php://output");
    }

    public function exportPDF($template_id){
        $model = RatingTemplate::find($template_id);
        $page_title = $model->name;
        $categories = RatingCategory::where('template_id', '=', $model->id)->get();

        $pdf = PDF::loadView('rating::backend.template.form_view_pdf', [
            'model' => $model,
            'page_title' => $page_title,
            'categories' => $categories,
        ]);

        return $pdf->download(Str::slug($page_title).'.pdf');
    }

    //Kết quả đánh giá sau khóa học - cũ
    public function result($course_id, $type)
    {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        if ($type == 1){
            $course = OnlineCourse::find($course_id);
            $page_title = $course->name;
        } else{
            $course = OfflineCourse::find($course_id);
            $page_title = $course->name;
        }

        return view('rating::backend.rating_result.index', [
            'course_id' => $course_id,
            'type' => $type,
            'course' => $course,
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'page_title' => $page_title,
        ]);
    }

    public function getDataResult($course_id, $type, Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = RatingCourse::query();
        $query->select([
            'a.*',
            'b.code',
            'b.lastname',
            'b.firstname',
            'b.email',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.name AS parent_name',
            'f.name AS secondary_name',
            'f.code AS user_secon_code',
            'f.email AS user_secon_email',
        ]);
        $query->from('el_rating_course AS a');
        $query->leftJoin('el_profile AS b', function ($join) {
            $join->on('b.user_id', '=', 'a.user_id')
                ->where('a.user_type', '=', 1);
        });
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code','=', 'b.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code','=', 'd.parent_code');
        $query->leftJoin('el_quiz_user_secondary AS f', function ($join){
            $join->on('f.id', '=', 'a.user_id')
                ->where('a.user_type', '=', 2);
        });
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.type', '=', $type);
        $query->where('a.send', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('f.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('f.name', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('b.status', '=', $status);
        }
        if ($request->area) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.unit_code', $unit_id);
                $sub_query->orWhere('d.id', '=', $unit->id);
            });
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('b.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->profile_name = $row->lastname .' '. $row->firstname;
            $row->edit_url = route('module.rating.result.view', ['course_id' => $course_id, 'type' => $type, 'user_id' => $row->user_id]);
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

    public function resultDetail($course_id, $type, $user_id) {
        $user_type = getUserType();
        if ($user_type == 1){
            $user = Profile::find($user_id);
            $unit = Unit::where('code', '=', $user->unit_code)->first();
            $title = Titles::where('code', '=', $user->title_code)->first();
        }else{
            $user = QuizUserSecondary::find($user_id);
            $unit = '';
            $title = '';
        }

        if($type == 1){
            $item = OnlineCourse::find($course_id);
            $page_title = $item->name;
        }else{
            $item = OfflineCourse::find($course_id);
            $page_title = $item->name;
        }

        $rating_course = RatingCourse::where('course_id', '=', $course_id)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', $user_type)
            ->where('type', '=', $type)->first();

        $rating_course_categories = RatingCourseCategory::where('rating_course_id', '=', $rating_course->id)->get();

        $rating_course_question = function ($course_category_id){
            return RatingCourseQuestion::where('course_category_id', '=', $course_category_id)->get();
        };

        $rating_course_answer = function($course_question_id){
            return RatingCourseAnswer::where('course_question_id', '=', $course_question_id)->get();
        };

        return view('rating::backend.rating_result.form', [
            'item' => $item,
            'rating_course_categories' => $rating_course_categories,
            'rating_course_question' => $rating_course_question,
            'rating_course_answer' => $rating_course_answer,
            'type' => $type,
            'rating_course' => $rating_course,
            'user' => $user,
            'unit' => $unit,
            'title' => $title,
            'page_title' => $page_title,
            'course_id' => $course_id,
            'user_type' => $user_type
        ]);
    }

    //Kết quả Đánh giá cấp độ
    public function listCourseRegister($course_id, $course_type){
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        if ($course_type == 1){
            $course = OnlineCourse::find($course_id);
            $page_title = $course->name;

            $template_rating_level_course = OnlineRatingLevel::where('course_id', '=', $course_id)->get();
        } else{
            $course = OfflineCourse::find($course_id);
            $page_title = $course->name;

            $template_rating_level_course = OfflineRatingLevel::where('course_id', '=', $course_id)->get();
        }

        return view('rating::backend.rating_level_result.list_register', [
            'course_id' => $course_id,
            'course_type' => $course_type,
            'course' => $course,
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'page_title' => $page_title,
            'template_rating_level_course' => $template_rating_level_course,
        ]);
    }

    public function getDataCourseRegister($course_id, $course_type, Request $request) {
        $search = $request->input('search');
        $unit = $request->unit;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseRegisterView::query()
            ->from('el_course_register_view')
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', $course_type)
            ->where('user_type', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('full_name', 'like', '%'. $search .'%');
            });
        }
        if ($request->area) {
            $query->leftJoin('el_unit AS b', 'b.code', '=', 'el_course_register_view.unit_code');
            $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('unit_code', $unit_id);
                $sub_query->orWhere('unit_id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            if ($course_type == 1){
                for($level = 1; $level <= 4; $level++){
                    $num = 0;

                    $rating_level_object = OnlineRatingLevelObject::query()
                        ->whereIn('online_rating_level_id', function ($sub) use ($course_id, $level){
                            $sub->select(['id'])
                                ->from('el_online_rating_level')
                                ->where('course_id', '=', $course_id)
                                ->where('level', '=', $level)
                                ->pluck('id')
                                ->toArray();
                        })
                        ->where('course_id', '=', $course_id)
                        ->get();
                    foreach ($rating_level_object as $item){
                        if ($item->object_type == 1){
                            $num += 1;
                        }
                        if ($item->object_type == 2){
                            $num += 1;
                        }
                        if ($item->object_type == 3){
                            $colleague = OnlineRatingLevelObjectColleague::query()
                                ->where('online_rating_level_id', '=', $item->offline_rating_level_id)
                                ->where('rating_user_id', '=', $row->user_id)
                                ->count('user_id');
                            $num += $colleague;
                        }
                        if ($item->object_type == 4 && $item->rating_user_id == $row->user_id){
                            $num += count(explode(',', $item->user_id));
                        }
                    }

                    $course_rating_level = RatingLevelCourse::query()
                        ->where('course_id', '=', $course_id)
                        ->where('course_type', '=', $course_type)
                        ->where('level', '=', $level)
                        ->where('rating_user', '=', $row->user_id)
                        ->where('send', '=', 1)
                        ->count();
                    $row->{'level'.$level} = $course_rating_level.'/'.$num;
                }
            } else{
                for($level = 1; $level <= 4; $level++){
                    $num = 0;

                    $rating_level_object = OfflineRatingLevelObject::query()
                        ->whereIn('offline_rating_level_id', function ($sub) use ($course_id, $level){
                            $sub->select(['id'])
                                ->from('el_offline_rating_level')
                                ->where('course_id', '=', $course_id)
                                ->where('level', '=', $level)
                                ->pluck('id')
                                ->toArray();
                        })
                        ->where('course_id', '=', $course_id)
                        ->get();
                    foreach ($rating_level_object as $item){
                        if ($item->object_type == 1){
                            $num += 1;
                        }
                        if ($item->object_type == 2){
                            $num += 1;
                        }
                        if ($item->object_type == 3){
                            $colleague = OfflineRatingLevelObjectColleague::query()
                                ->where('offline_rating_level_id', '=', $item->offline_rating_level_id)
                                ->where('rating_user_id', '=', $row->user_id)
                                ->count('user_id');
                            $num += $colleague;
                        }
                        if ($item->object_type == 4 && $item->rating_user_id == $row->user_id){
                            $num += count(explode(',', $item->user_id));
                        }
                    }

                    $course_rating_level = RatingLevelCourse::query()
                        ->where('course_id', '=', $course_id)
                        ->where('course_type', '=', $course_type)
                        ->where('level', '=', $level)
                        ->where('rating_user', '=', $row->user_id)
                        ->where('send', '=', 1)
                        ->count();
                    $row->{'level'.$level} = $course_rating_level.'/'.$num;
                }
            }

            $row->result = route('module.rating_level.result.index', [$course_id, $course_type, $row->user_id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function resultRatingLevel($course_id, $course_type, $user_id){
        if ($course_type == 1){
            $course = OnlineCourse::find($course_id);
            $page_title = $course->name;
        } else{
            $course = OfflineCourse::find($course_id);
            $page_title = $course->name;
        }

        $full_name = Profile::fullname($user_id);
        return view('rating::backend.rating_level_result.index', [
            'course_id' => $course_id,
            'course_type' => $course_type,
            'course' => $course,
            'page_title' => $page_title,
            'full_name' => $full_name,
            'user_id' => $user_id
        ]);
    }

    public function getDataResultRatingLevel($course_id, $course_type, $user_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $profile = Profile::whereUserId($user_id)->first();

        $query = RatingLevelCourse::query()
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', $course_type)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', 1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $unit = @$profile->unit;
            $parent_unit = @$unit->parent;

            $row->code = $profile->code;
            $row->full_name = $profile->full_name;
            $row->unit_name = @$unit->name;
            $row->parent_unit_name = @$parent_unit->name;
            $row->rating_level = 'Cấp độ '.$row->level;
            if ($row->send == 1){
                $row->rating_time = get_date($row->updated_at);

                $row->rating_status = 'Đã đánh giá';
            }else{
                $row->rating_status = 'Chưa đánh giá';
            }

            $row->result = route('module.rating_level.result.view', [$course_id, $course_type, $user_id, $row->course_rating_level_id]);
            $row->export_word = route('module.rating_level.result.export_word', [$course_id, $course_type, $user_id, $row->course_rating_level_id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function resultRatingLevelDetail($course_id, $course_type, $user_id, $course_rating_level_id) {
        if($course_type == 1){
            $item = OnlineCourse::find($course_id);
            $page_title = $item->name;
        }else{
            $item = OfflineCourse::find($course_id);
            $page_title = $item->name;
        }

        $rating_level_course = RatingLevelCourse::query()
            ->where('course_rating_level_id', '=', $course_rating_level_id)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', 1)
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', $course_type)
            ->first();

        $rating_course_categories = RatingLevelCourseCategory::where('rating_level_course_id', '=', $rating_level_course->id)->get();
        $full_name = Profile::fullname($user_id);

        return view('rating::backend.rating_level_result.form', [
            'item' => $item,
            'rating_course_categories' => $rating_course_categories,
            'course_type' => $course_type,
            'page_title' => $page_title,
            'course_id' => $course_id,
            'full_name' => $full_name,
            'user_id' => $user_id,
        ]);
    }

    public function exportWordRatingLevelDetail($course_id, $course_type, $user_id, $course_rating_level_id) {
        $full_name = Profile::fullname($user_id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText(Str::upper('Bài đánh giá'), [
            'name'=>'Times New Roman',
            'size' => 14,
            'bold' => true,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);

        $section->addText(' ');

        $arrawser = range('a', 'z');

        $rating_level_course = RatingLevelCourse::query()
            ->where('course_rating_level_id', '=', $course_rating_level_id)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', 1)
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', $course_type)
            ->first();
        $rating_course_categories = RatingLevelCourseCategory::where('rating_level_course_id', '=', $rating_level_course->id)->get();

        foreach ($rating_course_categories as $cate_key => $category) {
            $section->addText(Str::upper($category->category_name), [
                'name'=>'Times New Roman',
                'size' => 12,
                'bold' => true,
            ]);

            $section->addText(' ');

            $questions = $category->questions;
            foreach ($questions as $ques_key => $question){
                switch ($question->type){
                    case 'choice' : $type = 'Trắc nghiệm'; break;
                    case 'essay' : $type = 'Tự luận'; break;
                    case 'text' : $type = 'Nhập text'; break;
                    case 'matrix' : $type = 'Ma trận'; break;
                    case 'matrix_text' : $type = 'Ma trận (nhập text)'; break;
                    case 'dropdown' : $type = 'Lựa chọn'; break;
                    case 'sort' : $type = 'Sắp xếp'; break;
                    case 'percent' : $type = 'Phần trăm'; break;
                    case 'number' : $type = 'Nhập số'; break;
                    case 'time' : $type = 'Thời gian'; break;
                }

                $section->addText(str_repeat(' ', 3) . ($ques_key + 1).'. '.$question->question_name .' ('. $type . ')', [
                    'name'=>'Times New Roman',
                    'size' => 12,
                ]);

                if ($question->type == 'essay' || $question->type == 'time') {
                    $section->addText($question->answer_essay);
                }

                $answers = $question->answers->where('is_row', '=', 1);
                $answers_col = $question->answers->where('is_row', '=', 0);
                $answers_row_col = $question->answers->where('is_row', '=', 10)->first();
                if ($question->type == 'sort') {
                    foreach ($answers as $answer){
                        $section->addText(str_repeat(' ', 5) . $answer->text_answer .' '. $answer->answer_name, [
                            'name' => 'Times New Roman',
                            'size' => 12,
                        ]);
                    }
                }
                if ($question->type == 'dropdown'){
                    foreach ($answers as $answer) {
                        if ($question->answer_essay == $answer->answer_id){
                            $section->addText(str_repeat(' ', 5) .'[X] '. $answer->answer_name, [
                                'name' => 'Times New Roman',
                                'size' => 12,
                                'bold' => true,
                            ]);
                        }else{
                            $section->addText(str_repeat(' ', 5) .'[] '. $answer->answer_name, [
                                'name' => 'Times New Roman',
                                'size' => 12,
                            ]);
                        }
                    }
                }
                if ($question->type == 'choice'){
                    foreach ($answers as $answer) {
                        if ($answer->is_check){
                            $section->addText(str_repeat(' ', 5) .'[X] '. $answer->answer_name, [
                                'name' => 'Times New Roman',
                                'size' => 12,
                                'bold' => true,
                            ]);
                        }else{
                            $section->addText(str_repeat(' ', 5) .'[] '. $answer->answer_name, [
                                'name' => 'Times New Roman',
                                'size' => 12,
                            ]);
                        }
                        if($answer->is_text == 1){
                            $section->addText(str_repeat(' ', 5) . $answer->text_answer, [
                                'name' => 'Times New Roman',
                                'size' => 12,
                            ]);
                        }
                    }
                }
                if (in_array($question->type, ['text', 'percent', 'number'])) {
                    foreach ($answers as $answer){
                        $section->addText(str_repeat(' ', 5) . $answer->answer_name .' '. $answer->text_answer);
                    }
                }
                if (in_array($question->type, ['matrix', 'matrix_text'])){
                    $styleTable = array('borderSize' => 6, 'borderColor' => '000', 'cellMargin' => 80);
                    $styleFirstRow = array('borderBottomSize' => 6, 'borderBottomColor' => '0000FF');
                    $styleCell = array('valign' => 'center', 'align' => 'center');
                    $fontStyle = array('bold' => true, 'align' => 'center', 'valign' => 'center',);
                    $phpWord->addTableStyle('Table', $styleTable, $styleFirstRow);
                    $table = $section->addTable('Table');

                    $table->addRow(500);
                    $table->addCell(1000)->addText(isset($answers_row_col) ? $answers_row_col->name : '');
                    foreach ($answers_col as $answer){
                        $table->addCell(1000)->addText($answer->answer_name, $fontStyle);
                    }

                    foreach ($answers as $answer) {
                        $check_answer_matrix = $answer->check_answer_matrix ? json_decode($answer->check_answer_matrix) : [];
                        $answer_matrix = json_decode($answer->answer_matrix);

                        $table->addRow(500);
                        $table->addCell(1000)->addText($answer->answer_name);

                        foreach ($answers_col as $ans_key => $item) {
                            if($question->type == 'matrix'){
                                $table->addCell(1000, $styleCell)->addText(in_array($item->answer_id, $check_answer_matrix) ? '[X]' : '');
                            }else{
                                $table->addCell(1000)->addText(isset($answer_matrix) ? $answer_matrix[$ans_key-1] : '');
                            }
                        }
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
        $file_name = Str::slug('Bài đánh giá '.$full_name);
        header("Content-Disposition: attachment; filename=". $file_name .".docx");
        $objWriter->save("php://output");
    }

    public function listReportRatingLevel($course_id, $course_type){
        if ($course_type == 1){
            $course = OnlineCourse::find($course_id);
            $page_title = $course->name;
        } else{
            $course = OfflineCourse::find($course_id);
            $page_title = $course->name;
        }

        return view('rating::backend.rating_level_result.list_report', [
            'course_id' => $course_id,
            'course_type' => $course_type,
            'course' => $course,
            'page_title' => $page_title,
        ]);
    }

    public function getdataListReportRatingLevel($course_id, $course_type, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        if ($course_type == 1){
            $query = OnlineRatingLevel::where('course_id', '=', $course_id);
        } else{
            $query = OfflineRatingLevel::where('course_id', '=', $course_id);
        }

        if ($search) {
            $query->where('rating_name', 'like', '%'.$search.'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $num = 0;

            if ($course_type == 1){
                $rating_level_object = OnlineRatingLevelObject::query()
                    ->where('online_rating_level_id', $row->id)
                    ->where('course_id', '=', $course_id)
                    ->get();
                foreach ($rating_level_object as $item){
                    if ($item->object_type == 1){
                        $num += OnlineRegister::whereCourseId($course_id)->whereStatus(1)->count();
                    }
                    if ($item->object_type == 2){
                        $num += OnlineRegisterView::whereCourseId($course_id)->groupBy('unit_id')->count();
                    }
                    if ($item->object_type == 3){
                        $colleague = OnlineRatingLevelObjectColleague::query()
                            ->where('online_rating_level_id', '=', $item->online_rating_level_id)
                            ->count('user_id');
                        $num += $colleague;
                    }
                    if ($item->object_type == 4){
                        $num += count(explode(',', $item->user_id));
                    }
                }

                $course_rating_level = RatingLevelCourse::query()
                    ->where('course_rating_level_id', '=', $row->id)
                    ->where('course_id', '=', $course_id)
                    ->where('course_type', '=', $course_type)
                    ->where('level', '=', $row->level)
                    ->where('send', '=', 1)
                    ->count();
            } else{
                $rating_level_object = OfflineRatingLevelObject::query()
                    ->where('offline_rating_level_id', $row->id)
                    ->where('course_id', '=', $course_id)
                    ->get();
                foreach ($rating_level_object as $item){
                    if ($item->object_type == 1){
                        $num += OfflineRegister::whereCourseId($course_id)->whereStatus(1)->count();
                    }
                    if ($item->object_type == 2){
                        $num += OfflineRegisterView::whereCourseId($course_id)->groupBy('unit_id')->count();
                    }
                    if ($item->object_type == 3){
                        $colleague = OfflineRatingLevelObjectColleague::query()
                            ->where('offline_rating_level_id', '=', $item->offline_rating_level_id)
                            ->count('user_id');
                        $num += $colleague;
                    }
                    if ($item->object_type == 4){
                        $num += count(explode(',', $item->user_id));
                    }
                }

                $course_rating_level = RatingLevelCourse::query()
                    ->where('course_rating_level_id', '=', $row->id)
                    ->where('course_id', '=', $course_id)
                    ->where('course_type', '=', $course_type)
                    ->where('level', '=', $row->level)
                    ->where('send', '=', 1)
                    ->count();
            }

            $row->count_user = $course_rating_level . '/' . $num;
            $row->export = route('module.rating_level.report', [$course_id, $course_type, $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function reportRatingLevel($course_id, $course_type, $course_rating_level_id, Request $request){
        $course_rating_level_object_id = $request->course_rating_level_object_id ? $request->course_rating_level_object_id : 0;
        return (new ReportExport($course_id, $course_type, $course_rating_level_id, $course_rating_level_object_id))->download('bao_cao_ket_qua_danh_gia_cap_do_'. date('d_m_Y') .'.xlsx');
    }
}
