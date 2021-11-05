<?php

namespace Modules\Survey\Http\Controllers;

use App\Permission;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyQuestionAnswer2;
use Modules\Survey\Entities\SurveyQuestionCategory;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyTemplate2;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\SurveyUserAnswer;
use Modules\Survey\Entities\SurveyUserAnswerMatrix;
use Modules\Survey\Entities\SurveyUserCategory;
use Modules\Survey\Entities\SurveyUserExport;
use Modules\Survey\Entities\SurveyUserQuestion;
use function GuzzleHttp\Promise\inspect_all;
use \Carbon\Carbon;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $profile = Profile::find(Auth::id());
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        Survey::addGlobalScope(new CompanyScope());
        $query = Survey::query();
        $query->where('status', '=', 1);
        if (!Permission::isAdmin()) {
            $query->where(function ($subquery) use ($profile, $unit, $title) {
                $subquery->orWhereIn('id', function ($subquery2) use ($profile, $unit, $title) {
                    $subquery2->select(['survey_id'])
                        ->from('el_survey_object')
                        ->where('user_id', '=', $profile->user_id)
                        ->orWhere('title_id', '=', @$title->id)
                        ->orWhere('unit_id', '=', @$unit->id);
                });
            });
        }
        $query->orderBy('id', 'desc');
        $surveys = $query->paginate(8);

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($surveys);
            return $data;
        }

        if (url_mobile()){
            return view('themes.mobile.frontend.survey.index', compact('surveys'));
        }

        return view('survey::frontend.index',compact('surveys'));
    }

    public function getData(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $profile = Profile::find(Auth::id());
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $query = Survey::query();
        $query->select([
            'a.id',
            'a.name',
            'a.start_date',
            'a.end_date',
            'a.template_id',
            'a.status',
            'a.created_by',
        ]);
        $query->from('el_survey AS a');
        $query->where('a.status', '=', 1);

        if (!Permission::isAdmin()){
            $query->where(function ($subquery) use ($profile, $unit, $title) {
                $subquery->orWhereIn('a.id', function ($subquery2) use ($profile, $unit, $title) {
                    $subquery2->select(['survey_id'])
                        ->from('el_survey_object')
                        ->where('user_id', '=', $profile->user_id)
                        ->orWhere('title_id', '=', $title->id)
                        ->orWhere('unit_id', '=', $unit->id);
                });
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $template = SurveyTemplate::find($row->template_id);
            $count_ques = SurveyQuestion::whereIn('category_id', function ($subquery) use ($template){
                $subquery->select(['id']);
                $subquery->from('el_survey_template_question_category');
                $subquery->where('template_id', '=', $template->id);
            })->count();

            $survey_user = SurveyUser::where('survey_id', '=', $row->id)
                ->where('user_id', '=', Auth::id())->first();

            if (is_null($survey_user)) {
                $row->survey_user = 1;
            }elseif ($survey_user && $survey_user->send == 1){
                $row->survey_user = 2;
            }else {
                $row->survey_user = 3;
            }

            $row->get_survey_user = route('module.survey.user', ['id' => $row->id]);
            $row->edit_survey_user = route('module.survey.user.edit', ['id' => $row->id]);

            $row->count_ques = $count_ques;
            $row->date = get_date($row->start_date, 'H:i d/m/Y') . ' <i class="fa fa-long-arrow-right"></i> ' . get_date($row->end_date, 'H:i d/m/Y');

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getSurveyUser($id) {
        $profile = Profile::find(Auth::id());
        $item = Survey::findOrFail($id);
        $template = SurveyTemplate2::whereSurveyId($item->id)->firstOrFail();

        if (url_mobile()){
            return view('themes.mobile.frontend.survey.survey', [
                'item' => $item,
                'template' => $template,
            ]);
        }

        return view('survey::modal.survey', [
            'item' => $item,
            'profile' => $profile,
            'template' => $template,
        ]);
    }

    public function editSurveyUser($id) {
        $profile = Profile::find(Auth::id());
        $item = Survey::findOrFail($id);

        $survey_user = SurveyUser::where('survey_id', '=', $item->id)
            ->where('user_id', '=', Auth::id())->first();

        $survey_user_categories = SurveyUserCategory::where('survey_user_id', '=', $survey_user->id)->get();

        $question_errors = session()->get('error');
        session()->forget('error');

        if (url_mobile()){
            return view('themes.mobile.frontend.survey.edit_survey', [
                'item' => $item,
                'survey_user' => $survey_user,
                'survey_user_categories' => $survey_user_categories,
                'question_errors' => $question_errors,
            ]);
        }

        return view('survey::modal.edit_survey', [
            'item' => $item,
            'profile' => $profile,
            'survey_user' => $survey_user,
            'survey_user_categories' => $survey_user_categories,
            'question_errors' => $question_errors,
        ]);
    }

    public function saveSurveyUser(Request $request){
        $this->validateRequest([
            'survey_id' => 'required',
        ], $request);

        $errors = [];
        $title_report = [];
        $content_report = [];

        $survey_user_id = $request->survey_user_id;
        $template_id = $request->template_id;
        $survey_id = $request->survey_id;

        $user_category_id = $request->user_category_id;
        $category_id = $request->category_id;
        $category_name = $request->category_name;

        $user_question_id = $request->user_question_id;
        $question_id = $request->question_id;
        $question_code = $request->question_code;
        $question_name = $request->question_name;
        $type = $request->type;
        $multiple = $request->multiple;
        $answer_essay = $request->answer_essay;

        $user_answer_id = $request->user_answer_id;
        $answer_id = $request->answer_id;
        $answer_code = $request->answer_code;
        $answer_name = $request->answer_name;
        $is_text = $request->is_text;
        $text_answer = $request->text_answer;
        $is_check = $request->is_check;
        $is_row = $request->is_row;
        $answer_matrix = $request->answer_matrix;
        $check_answer_matrix = $request->check_answer_matrix;

        $send = $request->send;
        $more_suggestions = $request->more_suggestions;

        $answer_matrix_code = $request->answer_matrix_code;

        $model = SurveyUser::firstOrNew(['id' => $survey_user_id, 'user_id' =>  Auth::id(), 'survey_id' => $survey_id]);
        $model->user_id = Auth::id();
        $model->survey_id = $survey_id;
        $model->send = $send;
        $model->template_id = $template_id;
        $model->more_suggestions = $more_suggestions ? $more_suggestions : '';
        $model->save();

        foreach($category_id as $cate_key => $cate_id){
            $categories = SurveyUserCategory::firstOrNew(['id' => $user_category_id[$cate_key]]);
            $categories->survey_user_id = $model->id;
            $categories->category_id = $cate_id;
            $categories->category_name = $category_name[$cate_id];
            $categories->save();

            if(isset($question_id[$cate_id])){
                foreach($question_id[$cate_id] as $ques_key => $ques_id){
                    $user_ques_id = $user_question_id[$cate_id][$ques_key];
                    $ques_code = $question_code[$cate_id][$ques_id];
                    $ques_name = $question_name[$cate_id][$ques_id];

                    $survey_user_question = SurveyUserQuestion::firstOrNew(['id' => $user_ques_id]);
                    $survey_user_question->survey_user_category_id = $categories->id;
                    $survey_user_question->question_id = $ques_id;
                    $survey_user_question->question_code = isset($ques_code) ? $ques_code : null;
                    $survey_user_question->question_name = $ques_name;
                    $survey_user_question->type = $type[$cate_id][$ques_id];
                    $survey_user_question->multiple = $multiple[$cate_id][$ques_id];
                    $survey_user_question->answer_essay = isset($answer_essay[$cate_id][$ques_id]) ? $answer_essay[$cate_id][$ques_id] : '';
                    $survey_user_question->save();

                    if ($survey_user_question->type == 'choice' && $survey_user_question->multiple == 0){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                    }
                    if ($survey_user_question->type == 'essay' || $survey_user_question->type == 'time'){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                        $content_report[] = isset($survey_user_question->answer_essay) ? $survey_user_question->answer_essay : 'null';
                    }
                    if ($survey_user_question->type == 'dropdown'){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                        $content_report[] = isset($answer_code[$cate_id][$ques_id][$survey_user_question->answer_essay]) ? $answer_name[$cate_id][$ques_id][$survey_user_question->answer_essay] : 'null';
                    }

                    if(isset($answer_id[$cate_id][$ques_id])){
                        if($survey_user_question->type == 'percent'){
                            $total = 0;
                            $arr_answer_percent = $text_answer[$cate_id][$ques_id];
                            foreach ($arr_answer_percent as $percent){
                                $total += floatval(preg_replace("/[^0-9]/", '', $percent));
                            }

                            if ($total > 100){
                                $errors[] = 'Tổng phần trăm câu hỏi: "'. $ques_name . '" vượt quá 100';
                            }
                        }

                        foreach($answer_id[$cate_id][$ques_id] as $ans_key => $ans_id){
                            $user_ans_id = $user_answer_id[$cate_id][$ques_id][$ans_key];
                            $ans_code = $answer_code[$cate_id][$ques_id][$ans_id];
                            $ans_name = $answer_name[$cate_id][$ques_id][$ans_id];
                            $text = $is_text[$cate_id][$ques_id][$ans_id];
                            $row = $is_row[$cate_id][$ques_id][$ans_id];

                            $survey_user_answer = SurveyUserAnswer::firstOrNew(['id' => $user_ans_id]);
                            $survey_user_answer->survey_user_question_id = $survey_user_question->id;
                            $survey_user_answer->answer_id = $ans_id;
                            $survey_user_answer->answer_code = isset($ans_code) ? $ans_code : '';
                            $survey_user_answer->answer_name = isset($ans_name) ? $ans_name : '';
                            $survey_user_answer->is_text = $text;
                            $survey_user_answer->is_row = $row;

                            if ($survey_user_question->multiple == 1){
                                $survey_user_answer->is_check = isset($is_check[$cate_id][$ques_id][$ans_id]) ? $is_check[$cate_id][$ques_id][$ans_id] : 0;

                                if ($survey_user_question->type == 'choice'){
                                    $title_report[] = isset($ans_code) ? $ans_code : 'null';
                                    $content_report[] = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : (isset($is_check[$cate_id][$ques_id][$ans_id]) ? 1 : 0);
                                }
                            }else{
                                if (isset($is_check[$cate_id][$ques_id]) && ($ans_id == $is_check[$cate_id][$ques_id])){
                                    $survey_user_answer->is_check = $ans_id;

                                    $content_report[] = (isset($ans_code) ? $ans_code : 'null') . (isset($text_answer[$cate_id][$ques_id][$ans_id]) ? ' - '.$text_answer[$cate_id][$ques_id][$ans_id] : '');
                                }else{
                                    $survey_user_answer->is_check = 0;
                                }
                            }

                            if($survey_user_question->type == 'percent'){
                                $survey_user_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) && $total <= 100 ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }else{
                                $survey_user_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }

                            $survey_user_answer->answer_matrix = isset($answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $survey_user_answer->check_answer_matrix = isset($check_answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($check_answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $survey_user_answer->save();
                            if ($survey_user_question->type == 'matrix' && $survey_user_question->multiple == 0 && $survey_user_answer->is_row == 1){
                                $title_report[] = isset($ans_code) ? $ans_code : 'null';

                                $arr_col_answer = SurveyQuestionAnswer2::where('survey_id', '=', $survey_id)
                                    ->where('question_id', '=', $survey_user_question->question_id)
                                    ->where('is_row', '=', 0)
                                    ->pluck('id')->toArray();

                                $item_check = $check_answer_matrix[$cate_id][$ques_id][$ans_id][0];
                                foreach ($arr_col_answer as $key => $item){
                                    if (isset($item_check) && $item == $item_check){
                                        $content_report[] = ($key + 1);
                                    }
                                }
                            }
                        }

                        if (in_array($survey_user_question->type, ['text', 'sort', 'percent', 'number'])){
                            $arr_export = SurveyUserAnswer::whereSurveyUserQuestionId($survey_user_question->id)->get();
                            foreach ($arr_export as $export){
                                $title_report[] = isset($export->answer_code) ? $export->answer_code : 'null';
                                $content_report[] = isset($export->text_answer) ? $export->text_answer : 'null';
                            }
                        }
                    }

                    if (($survey_user_question->type == 'matrix' && $survey_user_question->multiple == 1) || $survey_user_question->type == 'matrix_text'){
                        if(isset($answer_matrix_code[$cate_id][$ques_id])) {
                            foreach ($answer_matrix_code[$cate_id][$ques_id] as $ans_key => $matrix) {

                                $answer_matrix_text = isset($answer_matrix[$cate_id][$ques_id][$ans_key]) ? $answer_matrix[$cate_id][$ques_id][$ans_key] : '';

                                $i = 0;
                                foreach ($matrix as $matrix_key => $matrix_code){
                                    SurveyUserAnswerMatrix::query()
                                        ->updateOrCreate([
                                            'survey_user_question_id' => $survey_user_question->id,
                                            'answer_row_id' => $ans_key,
                                            'answer_col_id' => $matrix_key
                                        ],[
                                            'answer_code' => $matrix_code
                                        ]);

                                    $title_report[] = isset($matrix_code) ? $matrix_code : 'null';

                                    $check = isset($check_answer_matrix[$cate_id][$ques_id][$ans_key]) ? $check_answer_matrix[$cate_id][$ques_id][$ans_key] : [];

                                    if(($survey_user_question->type == 'matrix' && $survey_user_question->multiple == 1)){
                                        $content_report[] = in_array($matrix_key, $check) ? 1 : 0;
                                    }

                                    if($survey_user_question->type == 'matrix_text'){
                                        $content_report[] = $answer_matrix_text ? $answer_matrix_text[$i] : 'null';
                                    }

                                    $i += 1;
                                }
                            }
                        }
                    }
                }
            }
        }

        session()->forget('error');
        if (count($errors) > 0){
            session()->push('error', $errors);
            session()->save();
        }

       //dd($title_report, $content_report);

        if ($send == 1){
            if (count($title_report) > 0){
                foreach ($title_report as $key => $title){
                    $export = new SurveyUserExport();
                    $export->user_id = Auth::id();
                    $export->survey_id = $survey_id;
                    $export->title = $title;
                    $export->content = isset($content_report[$key]) ? $content_report[$key] : '';
                    $export->save();
                }
            }

            $setting = PromotionCourseSetting::where('course_id', $survey_id)
                ->where('type', 4)
                ->where('status',1)
                ->where('code', '=', 'complete')
                ->first();

            if ($setting && $setting->point) {
                $user_point = PromotionUserPoint::firstOrCreate([
                    'user_id' => $model->user_id
                ], [
                    'point' => 0,
                    'level_id' => 0
                ]);

                if ($setting->start_date && $setting->end_date){
                    if ($setting->start_date <= $model->updated_at && $model->updated_at <= $setting->end_date){
                        $user_point->point += $setting->point;
                    }
                }else{
                    $user_point->point += $setting->point;
                }

                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $model->user_id);
                $user_point->update();

                $this->saveHistoryPromotion($model->user_id, $setting->point, $setting->course_id, $setting->id);

            }
        }

        if (url_mobile()){
            return response()->json([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.survey'),
            ]);
        }

        if ($send == 1){
            json_result([
                'status' => 'success',
                'message' => 'Đã gửi thành công',
                'redirect' => route('module.survey'),
            ]);
        }else{
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.survey.user.edit', [$survey_id]),
            ]);
        }
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=', $point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function saveHistoryPromotion($user_id,$point,$course_id, $promotion_course_setting_id){
        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 4;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        $course_name = Survey::query()->find($course_id)->name;

        $model = new Notify();
        $model->user_id = $user_id;
        $model->subject = 'Thông báo đạt điểm thưởng khảo sát.';
        $model->content = 'Bạn đã đạt điểm thưởng là "'. $point .'" điểm khi thực hiện khảo sát "'. $course_name .'"';
        $model->url = null;
        $model->created_by = 0;
        $model->save();

        $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
        $redirect_url = route('module.notify.view', [
            'id' => $model->id,
            'type' => 1
        ]);

        $notification = new AppNotification();
        $notification->setTitle($model->subject);
        $notification->setMessage($content);
        $notification->setUrl($redirect_url);
        $notification->add($user_id);
        $notification->save();
    }

    public function loadData($items) {
        $data = '';
        $date = date('Y-m-d');
        foreach ($items as $item) {
            $survey = $item->users->where('id',auth()->id())->first();
            $format_endate = \Carbon\Carbon::parse($item->end_date)->format('Y-m-d');

            $data.='<div class="col-lg-3 col-md-4 p-1">';
            $data.='    <div class="fcrse_1 library">';
            $data.='        <div class="fcrse_img">';
            $data.='            <img alt="'.$item->name.'" class="lazy" src="'.($item->image ? image_file($item->image) : asset('images/survey_default.jpg')).'">';
            $data.='            <div class="course-overlay">';
            if ($survey && $survey->pivot->send == 1) {
                $data.='            <div class="badge_seller">'.trans('app.completed').'</div>';
            }
            $data.='            </div>';
            $data.='        </div>';
            $data.='        <div class="fcrse_content">';
            $data.='            <label class="crse14s">'.$item->name.'</label>';
            $data.='                <div class="vdtodt">';
            $data.='                    <span class="vdt14"><b>'.trans('app.time').' : </b> <br>';
            $data.='                        <strong>'.Carbon::parse($item->start_date)->format('H:i d/m/Y') .'</strong>  '.trans('app.to').'';
            $data.='                        <strong>'.Carbon::parse($item->end_date)->format('H:i d/m/Y') .'</strong> ';
            $data.='                    </span>';
            $data.='                </div>';
            $data.='                <div>';
            if (!$survey && ($date < $format_endate)) {
            $data.='                    <a href="'.route('module.survey.user', ['id' => $item->id]).'" class="btn btn-danger float-right">'.trans('app.take_survey').'</a>';
            } elseif (!$survey && ($date > $format_endate)) {
                $data.='                <button type="button" class="btn btn-danger float-right">'.trans('app.end_survey').'</button>';
            } elseif ($survey && $survey->pivot->send == 1) {
                $data.='                <a href="'.route('module.survey.user.edit', ['id' => $item->id]).'" class="btn btn-danger float-right">'.trans('app.view_survey').'</a>';
            } else {
                $data.='                <a href="'.route('module.survey.user.edit', ['id' => $item->id]).'" class="btn btn-danger float-right">'.trans('app.edit_survey').'</a>';
            }
            $data.='                </div>';
            $data.='            </div>';
            $data.='        </div>';
            $data.='    </div>';
            $data.='</div>';
        }
        return $data;
    }
}
