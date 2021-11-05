<?php

namespace Modules\Rating\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Rating\Entities\RatingTemplate;
use Modules\Rating\Entities\RatingCategory;
use Modules\Rating\Entities\RatingQuestion;
use Modules\Rating\Entities\RatingQuestionAnswer;
use Modules\Rating\Entities\RatingCourse;
use Modules\Rating\Entities\RatingCourseAnswer;
use Modules\Rating\Entities\RatingCourseQuestion;
use Modules\Rating\Entities\RatingCourseCategory;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Chart\Properties;

class FrontendController extends Controller
{
    public function index()
    {
        return view('rating::index');
    }

    public function getCourse($type, $id) {
        if($type == 1){
            $item = OnlineCourse::find($id);
        }else{
            $item = OfflineCourse::find($id);
        }

        $template = RatingTemplate::find($item->template_id);
        $category_templates = RatingCategory::getCategoryTemplate($item->template_id);

        $questions = function ($category_id) {
            return RatingQuestion::getQuestion($category_id);
        };

        $answers = function ($question_id) {
            return RatingQuestionAnswer::getAnswer($question_id);
        };

        $user_id = getUserId();
        $user_type = getUserType();
        $rating_course = RatingCourse::where('course_id', '=', $id)
        ->where('user_id', '=', $user_id)
        ->where('user_type', '=', $user_type)
        ->where('type', '=', $type)->first();

        if (url_mobile()){
            return view('rating::mobile.rating', [
                'item' => $item,
                'category_templates' => $category_templates,
                'questions' => $questions,
                'answers' => $answers,
                'type' => $type,
                'rating_course' => $rating_course,
            ]);
        }

        return view('rating::modal.rating', [
            'item' => $item,
            'type' => $type,
            'template' => $template
        ]);
    }

    public function editCourse($type, $id) {
        if($type == 1){
            $item = OnlineCourse::find($id);
        }else{
            $item = OfflineCourse::find($id);
        }

        $user_id = getUserId();
        $user_type = getUserType();
        $rating_course = RatingCourse::where('course_id', '=', $id)
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

        if (url_mobile()){
            return view('rating::mobile.edit_rating', [
                'item' => $item,
                'rating_course_categories' => $rating_course_categories,
                'rating_course_question' => $rating_course_question,
                'rating_course_answer' => $rating_course_answer,
                'type' => $type,
                'rating_course' => $rating_course,
            ]);
        }
        return view('rating::modal.edit_rating', [
            'item' => $item,
            'rating_course_categories' => $rating_course_categories,
            'type' => $type,
            'rating_course' => $rating_course,
        ]);
    }

    public function saveRatingCourse(Request $request){
        $user_id = getUserId();
        $user_type = getUserType();

        $template_id = $request->template_id;
        $course_id = $request->course_id;
        $course_type = $request->course_type;
        $rating_user_id = $request->rating_user_id;

        $user_category_id = $request->user_category_id;
        $category_id = $request->category_id;
        $category_name = $request->category_name;

        $user_question_id = $request->user_question_id;
        $question_id = $request->question_id;
        $question_name = $request->question_name;
        $type = $request->type;
        $multiple = $request->multiple;
        $answer_essay = $request->answer_essay;

        $user_answer_id = $request->user_answer_id;
        $answer_id = $request->answer_id;
        $answer_name = $request->answer_name;
        $is_text = $request->is_text;
        $text_answer = $request->text_answer;
        $is_check = $request->is_check;
        $is_row = $request->is_row;
        $answer_matrix = $request->answer_matrix;
        $check_answer_matrix = $request->check_answer_matrix;

        $send = $request->send;

        $model = RatingCourse::firstOrNew(['id' => $rating_user_id]);
        $model->user_id = $user_id;
        $model->user_type = $user_type;
        $model->course_id = $course_id;
        $model->type = $course_type;
        $model->send = $send;
        $model->template_id = $template_id;
        $model->save();

        foreach($category_id as $cate_key => $cate_id){
            $categories = RatingCourseCategory::firstOrNew(['id' => $user_category_id[$cate_key]]);
            $categories->rating_course_id = $model->id;
            $categories->category_id = $cate_id;
            $categories->category_name = $category_name[$cate_id];
            $categories->save();

            if(isset($question_id[$cate_id])){
                foreach($question_id[$cate_id] as $ques_key => $ques_id){
                    $course_question = RatingCourseQuestion::firstOrNew(['id' => $user_question_id[$cate_id][$ques_key]]);
                    $course_question->course_category_id = $categories->id;
                    $course_question->question_id = $ques_id;
                    $course_question->question_name = $question_name[$cate_id][$ques_id];
                    $course_question->type = $type[$cate_id][$ques_id];
                    $course_question->multiple = $multiple[$cate_id][$ques_id];
                    $course_question->answer_essay = isset($answer_essay[$cate_id][$ques_id]) ? $answer_essay[$cate_id][$ques_id] : '';
                    $course_question->save();

                    if(isset($answer_id[$cate_id][$ques_id])){
                        foreach($answer_id[$cate_id][$ques_id] as $ans_key => $ans_id){
                            $course_answer = RatingCourseAnswer::firstOrNew(['id' => $user_answer_id[$cate_id][$ques_id][$ans_key]]);
                            $course_answer->course_question_id = $course_question->id;
                            $course_answer->answer_id = $ans_id;
                            $course_answer->answer_name = isset($answer_name[$cate_id][$ques_id][$ans_id]) ? $answer_name[$cate_id][$ques_id][$ans_id] : '';
                            $course_answer->is_text = $is_text[$cate_id][$ques_id][$ans_id];
                            $course_answer->is_row = $is_row[$cate_id][$ques_id][$ans_id];
                            $course_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            $course_answer->answer_matrix = isset($answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($answer_matrix[$cate_id][$ques_id][$ans_id]) : '';
                            $course_answer->check_answer_matrix = isset($check_answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($check_answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            if ($course_question->multiple == 1){
                                $course_answer->is_check = isset($is_check[$cate_id][$ques_id][$ans_id]) ? $is_check[$cate_id][$ques_id][$ans_id] : 0;
                            }else{
                                $course_answer->is_check = isset($is_check[$cate_id][$ques_id]) && ($ans_id == $is_check[$cate_id][$ques_id]) ? $is_check[$cate_id][$ques_id] : 0;
                            }

                            $course_answer->save();
                        }
                    }

                }
            }
        }

        if ($send == 1){
            $setting = PromotionCourseSetting::where('course_id', $course_id)
                ->where('type', $type)
                ->where('status',1)
                ->where('code', '=', 'assessment_after_course')
                ->first();
            if ($setting && $setting->point){
                $user_point = PromotionUserPoint::firstOrCreate([
                    'user_id' => $user_id,
                    'user_type' => $user_type
                ], [
                    'point' => 0,
                    'level_id' => 0
                ]);
                $user_point->point += $setting->point;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_id);
                $user_point->update();

                $this->saveHistoryPromotion($user_id, $setting->point, $setting->course_id, $type, $setting->id);
            }
        }

        if (url_mobile()){
            $redirect = $course_type == 1 ? route('themes.mobile.frontend.online.detail', ['course_id' => $course_id]) : route('themes.mobile.frontend.offline.detail', ['course_id' => $course_id]);
        }else{
            $redirect = $course_type == 1 ? route('module.online.detail', ['id' => $course_id]) : route('module.offline.detail', ['id' => $course_id]);
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => $redirect,
        ]);
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=', $point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function saveHistoryPromotion($user_id,$point,$course_id, $type, $promotion_course_setting_id){
        $user_type = getUserType();

        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->user_type = $user_type;
        $history->point = $point;
        $history->type = $type;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        if ($type == 1){
            $course_name = OnlineCourse::query()->find($course_id)->name;
        }else{
            $course_name = OfflineCourse::query()->find($course_id)->name;
        }

        $model = new Notify();
        $model->user_id = $user_id;
        $model->subject = 'Thông báo đạt điểm thưởng khoá học.';
        $model->content = 'Bạn đã đạt điểm thưởng là "'. $point .'" điểm của khoá học "'. $course_name .'"';
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
}
