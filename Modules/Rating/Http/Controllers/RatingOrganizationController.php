<?php

namespace Modules\Rating\Http\Controllers;

use App\Automail;
use App\CourseRegisterView;
use App\CourseView;
use App\Models\Categories\Unit;
use App\ProfileView;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifyTemplate;
use Modules\Online\Entities\OnlineRatingLevel;
use Modules\Rating\Entities\CourseRatingLevel;
use Modules\Rating\Entities\CourseRatingLevelObject;
use Modules\Rating\Entities\CourseRatingLevelObjectColleague;
use Modules\Rating\Entities\RatingAnswerMatrix;
use Modules\Rating\Entities\RatingAnswerMatrix2;
use Modules\Rating\Entities\RatingCategory;
use Modules\Rating\Entities\RatingCategory2;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\Rating\Entities\RatingLevelCourseAnswer;
use Modules\Rating\Entities\RatingLevelCourseAnswerMatrix;
use Modules\Rating\Entities\RatingLevelCourseCategory;
use Modules\Rating\Entities\RatingLevelCourseExport;
use Modules\Rating\Entities\RatingLevelCourseQuestion;
use Modules\Rating\Entities\RatingLevels;
use Modules\Rating\Entities\RatingLevelsCourses;
use Modules\Rating\Entities\RatingLevelsRegister;
use Modules\Rating\Entities\RatingQuestion;
use Modules\Rating\Entities\RatingQuestion2;
use Modules\Rating\Entities\RatingQuestionAnswer;
use Modules\Rating\Entities\RatingQuestionAnswer2;
use Modules\Rating\Entities\RatingTemplate;
use Modules\Rating\Entities\RatingTemplate2;
use App\Models\Categories\Area;

class RatingOrganizationController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        // return view('rating::backend.rating_organization.index');
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

        RatingLevels::addGlobalScope(new DraftScope());
        $query = RatingLevels::query();

        if ($search) {
            $query->where(function ($sub) use ($search){
                $sub->orWhere('name', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.rating_organization.edit', ['id' => $row->id]);
            if (userCan('rating-levels-register')){
                $row->register_url = route('module.rating_organization.register', ['id' => $row->id]);
            }
            if (userCan('rating-levels-result')){
                $row->result_url = route('module.rating_organization.list_report', ['rating_levels_id' => $row->id]);
            }

            $rating_levels_register = RatingLevelsRegister::query()->where('rating_levels_id', '=', $row->id);
            if ($rating_levels_register->exists() && userCan('rating-levels-setting')){
                $row->setting_url = route('module.rating_organization.setting', ['rating_levels_id' => $row->id]);
            }

            $row->course = RatingLevelsCourses::query()->where('rating_levels_id', '=', $row->id)->count();

            $rating_levels_courses = RatingLevelsCourses::query()
                ->select(['b.code', 'b.name', 'b.start_date', 'b.end_date'])
                ->from('el_rating_levels_courses as a')
                ->leftJoin('el_course_view as b', function ($sub){
                    $sub->on('b.course_id', '=', 'a.course_id');
                    $sub->on('b.course_type', '=', 'a.course_type');
                })
                ->where('rating_levels_id', '=', $row->id)
                ->get();
            $list_courrse = '';
            foreach ($rating_levels_courses as $course){
                $list_courrse .= '('. $course->code .') '. $course->name .PHP_EOL. get_date($course->start_date) . ($course->end_date ? ' - '. get_date($course->end_date) : '') . PHP_EOL.PHP_EOL;
            }

            $row->list_course = $list_courrse;

            $course_rating_level_object = DB::query()
                ->select([
                    'a.course_rating_level_id',
                    'a.object_type',
                    'a.user_id',
                    'b.level',
                ])
                ->from('el_course_rating_level_object as a')
                ->leftJoin('el_course_rating_level as b', 'b.id', '=', 'a.course_rating_level_id')
                ->where('a.rating_levels_id', '=', $row->id)
                ->where('b.rating_levels_id', '=', $row->id)
                ->get();

            $num = 0;
            $course_rating_level = 0;
            foreach ($course_rating_level_object as $item){
                if ($item->object_type == 1){
                    $num += RatingLevelsRegister::where('rating_levels_id', $row->id)->count();
                }
                if ($item->object_type == 2){
                    $num += RatingLevelsRegister::query()->where('rating_levels_id', $row->id)
                        ->groupBy('unit_id')->count();
                }
                if ($item->object_type == 3){
                    $colleague = CourseRatingLevelObjectColleague::query()
                        ->where('course_rating_level_id', '=', $item->course_rating_level_id)
                        ->count('user_id');
                    $num += $colleague;
                }
                if ($item->object_type == 4){
                    $num += count(explode(',', $item->user_id));
                }

                $course_rating_level += RatingLevelCourse::query()
                    ->where('course_rating_level_id', '=', $item->course_rating_level_id)
                    ->where('course_id', '=', $row->id)
                    ->where('course_type', '=', 3)
                    ->where('level', '=', $item->level)
                    ->where('send', '=', 1)
                    ->count();
            }

            $row->count_user = $course_rating_level . '/' . $num;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $model = RatingLevels::query()->firstOrNew(['id' => $id]);
        $page_title = $id ? $model->name : trans('backend.add_new');

        $course_views = CourseView::whereStatus(1)->get();
        if ($id > 0){
            $rating_levels_courses = RatingLevelsCourses::query()
                ->select('b.id')
                ->from('el_rating_levels_courses as a')
                ->leftJoin('el_course_view as b', function ($sub){
                    $sub->on('b.course_id', '=', 'a.course_id');
                    $sub->on('b.course_type', '=', 'a.course_type');
                })
                ->where('rating_levels_id', '=', $id)
                ->pluck('b.id')->toArray();
        }else{
            $rating_levels_courses = [];
        }

        return view('rating::backend.rating_organization.form', [
            'model' => $model,
            'page_title' => $page_title,
            'course_views' => $course_views,
            'rating_levels_courses' => $rating_levels_courses,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'evaluate_training_effectiveness',
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'courses' => 'required',
        ], $request, [
            'name' => 'Tên kỳ đánh giá',
            'courses' => 'Khóa học',
        ]);

        $courses = $request->courses;

        $model = RatingLevels::firstOrNew(['id' => $request->id]);
        $model->name = $request->name;
        $model->status = $request->status;
        $model->save();

        RatingLevelsCourses::query()->where('rating_levels_id', '=', $model->id)->delete();
        foreach($courses as $course) {
            $course_view = CourseView::find($course);

            $rating_levels_courses = new RatingLevelsCourses();
            $rating_levels_courses->rating_levels_id = $model->id;
            $rating_levels_courses->course_id = $course_view->course_id;
            $rating_levels_courses->course_type = $course_view->course_type;
            $rating_levels_courses->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.rating_organization')
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        RatingLevels::destroy($ids);
        RatingLevelsCourses::query()->whereIn('rating_levels_id', $ids)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function open(Request $request)
    {
        $ids = $request->input('ids', null);
        $status = $request->status;

        RatingLevels::query()
        ->whereIn('id', $ids)
        ->update([
            'status' => $status
        ]);

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    //Thiết lập
    public function setting($rating_levels_id, Request $request){
        $templates = RatingTemplate::get();
        $rating_levels = RatingLevels::findOrFail($rating_levels_id);
        $profile = Profile::where('user_id', '>', 2)->get();

        return view('rating::backend.rating_organization.setting', [
            'rating_levels' => $rating_levels,
            'templates' => $templates,
            'profile' => $profile,
        ]);
    }

    public function getDataSetting($rating_levels_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseRatingLevel::query()
            ->where('rating_levels_id', '=', $rating_levels_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $rating_template = DB::query()
                ->from('el_course_rating_level_object as a')
                ->leftJoin('el_rating_template as b', 'b.id', '=', 'a.rating_template_id')
                ->where('a.course_rating_level_id', '=', $row->id)
                ->where('a.rating_levels_id', '=', $rating_levels_id)
                ->pluck('b.name')
                ->toArray();

            $row->rating_template = implode('; ', $rating_template);
            $row->modal_object_url = route('module.rating_organization.setting.modal_add_object', ['rating_levels_id' => $rating_levels_id, 'course_rating_level_id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveSetting($rating_levels_id, Request $request){
        $this->validateRequest([
            'level' => 'required',
            'rating_name' => 'required',
        ], $request, CourseRatingLevel::getAttributeName());

        $level = $request->level;
        $rating_name = $request->rating_name;
        $object_rating = $request->object_rating;

        $object_type = $request->object_type;
        $time_type = $request->time_type;
        $num_user = $request->num_user;
        $user_id = $request->user_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $num_date = $request->num_date;
        $object_view_rating = $request->object_view_rating;
        $user_completed = $request->user_completed;
        $rating_template_id = $request->rating_template_id;

        $text_object_type = [
            '1' => 'Học viên',
            '2' => 'Trưởng đơn vị',
            '3' => 'Đồng nghiệp',
            '4' => 'Khác',
        ];

        if (empty($object_rating)){
            json_message('Mời chọn đối tượng được đánh giá', 'error');
        }

        if (in_array(3, $object_type)){
            if (empty($num_user[3])){
                json_message('Mời nhập số lượng của Đối tượng đánh giá: '. $text_object_type[3], 'error');
            }
        }

        if (in_array(4, $object_type)){
            if (empty($user_id[4])){
                json_message('Mời chọn nhân viên đánh giá của Đối tượng đánh giá: '. $text_object_type[4], 'error');
            }
        }

        foreach ($object_type as $item){
            if ($time_type[$item] == 1){
                if (empty($start_date[$item]) || empty($end_date[$item])){
                    json_message('Khoảng thời gian không thể trống của Đối tượng đánh giá: '. $text_object_type[$item], 'error');
                }

                if (date_convert($end_date[$item]) < date_convert($start_date[$item])){
                    json_message('Khoảng thời gian không hợp lệ của Đối tượng đánh giá: '. $text_object_type[$item], 'error');
                }
            }
        }

        $course_rating_level = new CourseRatingLevel();
        $course_rating_level->rating_levels_id = $rating_levels_id;
        $course_rating_level->level = $level;
        $course_rating_level->rating_template_id = null;
        $course_rating_level->rating_name = $rating_name;
        $course_rating_level->object_rating = $object_rating;
        $course_rating_level->save();

        foreach ($object_type as $type) {
            $course_rating_level_object = new CourseRatingLevelObject();
            $course_rating_level_object->rating_levels_id = $rating_levels_id;
            $course_rating_level_object->course_rating_level_id = $course_rating_level->id;
            $course_rating_level_object->object_type = $type;
            $course_rating_level_object->time_type = isset($time_type[$type]) ? $time_type[$type] : null;
            $course_rating_level_object->start_date = isset($start_date[$type]) ? date_convert($start_date[$type]) : null;
            $course_rating_level_object->end_date = isset($end_date[$type]) ? date_convert($end_date[$type], '23:59:59') : null;
            $course_rating_level_object->num_date = isset($num_date[$type]) ? $num_date[$type] : null;
            $course_rating_level_object->user_id = isset($user_id[$type]) ? implode(',', $user_id[$type]) : null;
            $course_rating_level_object->rating_user_id = null;
            $course_rating_level_object->object_view_rating = isset($object_view_rating[$type]) ? $object_view_rating[$type] : 0;
            $course_rating_level_object->user_completed = isset($user_completed[$type]) ? $user_completed[$type] : 0;
            $course_rating_level_object->num_user = isset($num_user[$type]) ? $num_user[$type] : null;
            $course_rating_level_object->rating_template_id = isset($rating_template_id[$type]) ? $rating_template_id[$type] : null;
            $course_rating_level_object->save();

            if ($type == 1){
                $user_arr = RatingLevelsRegister::where('rating_levels_id', $rating_levels_id)->pluck('user_id')->toArray();
                foreach ($user_arr as $item){
                    $user = Profile::whereUserId($item)->first();
                    if ($user){
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $course_rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $rating_levels_id);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id]);
                    }
                }
            }
            if ($type == 2){
                $user_arr = DB::query()
                    ->select(['b.user_code'])
                    ->from('el_rating_levels_register as a')
                    ->leftJoin('el_unit_manager as b', 'b.unit_code', '=', 'a.unit_code')
                    ->where('a.rating_levels_id', $rating_levels_id)
                    ->groupBy('b.user_code')
                    ->pluck('b.user_code')->toArray();
                foreach ($user_arr as $item){
                    $user = Profile::whereCode($item)->first();
                    if ($user){
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $course_rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $rating_levels_id);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id]);
                    }
                }
            }
            if ($type == 4 && isset($user_id[4])){
                $user_arr = $user_id[4];
                foreach ($user_arr as $item){
                    $user = Profile::whereUserId($item)->first();
                    if ($user){
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $course_rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $rating_levels_id);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id]);
                    }
                }
            }

            $template = RatingTemplate::find($course_rating_level_object->rating_template_id)->toArray();

            $new_template = new RatingTemplate2();
            $new_template->fill($template);
            $new_template->id = $template['id'];
            $new_template->course_rating_level_id = $course_rating_level->id;
            $new_template->course_rating_level_object_id = $course_rating_level_object->id;
            $new_template->course_id = $rating_levels_id;
            $new_template->course_type = 3;
            $new_template->save();

            $categories = RatingCategory::query()->where('template_id', $template['id'])->get()->toArray();
            foreach ($categories as $category){
                $new_category = new RatingCategory2();
                $new_category->fill($category);
                $new_category->id = $category['id'];
                $new_category->course_rating_level_id = $course_rating_level->id;
                $new_category->course_rating_level_object_id = $course_rating_level_object->id;
                $new_category->course_id = $rating_levels_id;
                $new_category->course_type = 3;
                $new_category->save();

                $questions = RatingQuestion::query()->where('category_id', $category['id'])->get()->toArray();
                foreach ($questions as $question){
                    $new_question = new RatingQuestion2();
                    $new_question->fill($question);
                    $new_question->id = $question['id'];
                    $new_question->course_rating_level_id = $course_rating_level->id;
                    $new_question->course_rating_level_object_id = $course_rating_level_object->id;
                    $new_question->course_id = $rating_levels_id;
                    $new_question->course_type = 3;
                    $new_question->save();

                    $answers = RatingQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                    foreach ($answers as $answer){
                        $new_answer = new RatingQuestionAnswer2();
                        $new_answer->fill($answer);
                        $new_answer->id = $answer['id'];
                        $new_answer->course_rating_level_id = $course_rating_level->id;
                        $new_answer->course_rating_level_object_id = $course_rating_level_object->id;
                        $new_answer->course_id = $rating_levels_id;
                        $new_answer->course_type = 3;
                        $new_answer->save();
                    }

                    $answers_matrix = RatingAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                    foreach ($answers_matrix as $answer_matrix){
                        $new_answer_matrix = new RatingAnswerMatrix2();
                        $new_answer_matrix->fill($answer_matrix);
                        $new_answer_matrix->course_rating_level_id = $course_rating_level->id;
                        $new_answer_matrix->course_rating_level_object_id = $course_rating_level_object->id;
                        $new_answer_matrix->course_id = $rating_levels_id;
                        $new_answer_matrix->course_type = 3;
                        $new_answer_matrix->save();
                    }
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.rating_organization.setting', ['rating_levels_id' => $rating_levels_id])
        ]);
    }

    public function removeSetting($rating_levels_id, Request $request){
        $id = $request->id;

        CourseRatingLevel::where('id', $id)->where('rating_levels_id', '=', $rating_levels_id)->delete();
        CourseRatingLevelObject::where('course_rating_level_id', $id)->where('rating_levels_id', '=', $rating_levels_id)->delete();

        RatingTemplate2::where('course_rating_level_id', $id)->where('course_id', '=', $rating_levels_id)->where('course_type', 3)->delete();
        RatingCategory2::where('course_rating_level_id', $id)->where('course_id', '=', $rating_levels_id)->where('course_type', 3)->delete();
        RatingQuestion2::where('course_rating_level_id', $id)->where('course_id', '=', $rating_levels_id)->where('course_type', 3)->delete();
        RatingQuestionAnswer2::where('course_rating_level_id', $id)->where('course_id', '=', $rating_levels_id)->where('course_type', 3)->delete();
        RatingAnswerMatrix2::where('course_rating_level_id', $id)->where('course_id', '=', $rating_levels_id)->where('course_type', 3)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function modalAddObject($rating_levels_id, $course_rating_level_id){
        $rating_levels = RatingLevels::find($rating_levels_id);

        $course_rating_level = CourseRatingLevel::find($course_rating_level_id);
        $profile = Profile::where('user_id', '>', 2)->get();

        $course_rating_level_object = CourseRatingLevelObject::query()
            ->where('rating_levels_id', '=', $rating_levels_id)
            ->where('course_rating_level_id', '=', $course_rating_level_id)
            ->get()->toArray();

        $result_object = [];
        foreach ($course_rating_level_object as $object){
            $result_object[$object['object_type']] = $object;
        }

        $templates = RatingTemplate::get();
        return view('rating::modal.add_object_rating_level', [
            'rating_levels' => $rating_levels,
            'course_rating_level' => $course_rating_level,
            'profile' => $profile,
            'result_object' => $result_object,
            'templates' => $templates,
        ]);
    }

    public function getDataObject($rating_levels_id, $course_rating_level_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseRatingLevelObject::query()
            ->where('rating_levels_id', '=', $rating_levels_id)
            ->where('course_rating_level_id', '=', $course_rating_level_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            switch($row->object_type){
                case 1: $object_type = 'Học viên'; break;
                case 2: $object_type = 'Trưởng đơn vị'; break;
                case 3: $object_type = 'Đồng nghiệp'; break;
                case 4: $object_type = 'Khác'; break;
            }
            $row->object_type = $object_type;

            switch($row->object_view_rating){
                case 1: $object_view_rating = 'Học viện'; break;
                case 2: $object_view_rating = 'Trưởng đơn vị'; break;
                default: $object_view_rating = 'Không'; break;
            }
            $row->object_view_rating = $object_view_rating;

            switch($row->time_type){
                case 1: $time_type = 'Khoảng thời gian'; break;
                case 2: $time_type = 'Bắt đầu khóa'; break;
                case 3: $time_type = 'Kết thúc khóa'; break;
                case 4: $time_type = 'Hoàn thành khóa'; break;
                default: $time_type = ''; break;
            }
            $row->time_type = $time_type;

            if ($row->user_id){
                $user_id = explode(',', $row->user_id);
                $profile = ProfileView::query()->whereIn('user_id', $user_id)->pluck('full_name')->toArray();
                $row->list_user = implode('; ', $profile);
            }
            $row->rating_user = $row->rating_user_id ? Profile::fullname($row->rating_user_id) : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveObject($rating_levels_id, $course_rating_level_id, Request $request){
        $this->validateRequest([
            'object_type' => 'required',
        ], $request, [
            'object_type' => 'Loại đối tượng',
        ]);

        $rating_level = CourseRatingLevel::find($course_rating_level_id);

        $object_rating = $request->object_rating;
        $object_type = $request->object_type;
        $time_type = $request->time_type;
        $num_user = $request->num_user;
        $user_id = $request->user_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $num_date = $request->num_date;
        $object_view_rating = $request->object_view_rating;
        $user_completed = $request->user_completed;
        $rating_template_id = $request->rating_template_id;

        $text_object_type = [
            '1' => 'Học viên',
            '2' => 'Trưởng đơn vị',
            '3' => 'Đồng nghiệp',
            '4' => 'Khác',
        ];

        if (empty($object_rating)){
            json_message('Mời chọn đối tượng được đánh giá', 'error');
        }

        if (in_array(3, $object_type)){
            if (empty($num_user[3])){
                json_message('Mời nhập số lượng của Đối tượng đánh giá: '. $text_object_type[3], 'error');
            }
        }

        if (in_array(4, $object_type)){
            if (empty($user_id[4])){
                json_message('Mời chọn nhân viên đánh giá của Đối tượng đánh giá: '. $text_object_type[4], 'error');
            }
        }

        foreach ($object_type as $item){
            if ($time_type[$item] == 1){
                if (empty($start_date[$item]) || empty($end_date[$item])){
                    json_message('Khoảng thời gian không thể trống của Đối tượng đánh giá: '. $text_object_type[$item], 'error');
                }

                if (date_convert($end_date[$item]) < date_convert($start_date[$item])){
                    json_message('Khoảng thời gian không hợp lệ của Đối tượng đánh giá: '. $text_object_type[$item], 'error');
                }
            }
        }

        CourseRatingLevelObject::query()
            ->where('rating_levels_id', '=', $rating_levels_id)
            ->where('course_rating_level_id', '=', $course_rating_level_id)
            ->delete();

        RatingTemplate2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->delete();
        RatingCategory2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->delete();
        RatingQuestion2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->delete();
        RatingQuestionAnswer2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->delete();
        RatingAnswerMatrix2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->delete();

        foreach ($object_type as $type) {
            $course_rating_level_object = new CourseRatingLevelObject();
            $course_rating_level_object->rating_levels_id = $rating_levels_id;
            $course_rating_level_object->course_rating_level_id = $course_rating_level_id;
            $course_rating_level_object->object_type = $type;
            $course_rating_level_object->time_type = isset($time_type[$type]) ? $time_type[$type] : null;
            $course_rating_level_object->start_date = isset($start_date[$type]) ? date_convert($start_date[$type]) : null;
            $course_rating_level_object->end_date = isset($end_date[$type]) ? date_convert($end_date[$type], '23:59:59') : null;
            $course_rating_level_object->num_date = isset($num_date[$type]) ? $num_date[$type] : null;
            $course_rating_level_object->user_id = isset($user_id[$type]) ? implode(',', $user_id[$type]) : null;
            $course_rating_level_object->rating_user_id = null;
            $course_rating_level_object->object_view_rating = isset($object_view_rating[$type]) ? $object_view_rating[$type] : 0;
            $course_rating_level_object->user_completed = isset($user_completed[$type]) ? $user_completed[$type] : 0;
            $course_rating_level_object->num_user = isset($num_user[$type]) ? $num_user[$type] : null;
            $course_rating_level_object->rating_template_id = isset($rating_template_id[$type]) ? $rating_template_id[$type] : null;
            $course_rating_level_object->save();

            if ($type == 1){
                $user_arr = RatingLevelsRegister::where('rating_levels_id', $rating_levels_id)->pluck('user_id')->toArray();
                foreach ($user_arr as $item){
                    $user = Profile::whereUserId($item)->first();
                    if ($user){
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $rating_levels_id);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id]);
                    }
                }
            }
            if ($type == 2){
                $user_arr = DB::query()
                    ->select(['b.user_code'])
                    ->from('el_rating_levels_register as a')
                    ->leftJoin('el_unit_manager as b', 'b.unit_code', '=', 'a.unit_code')
                    ->where('a.rating_levels_id', $rating_levels_id)
                    ->groupBy('b.user_code')
                    ->pluck('b.user_code')->toArray();
                foreach ($user_arr as $item){
                    $user = Profile::whereCode($item)->first();
                    if ($user){
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $rating_levels_id);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id]);
                    }
                }
            }
            if ($type == 4 && isset($user_id[4])){
                $user_arr = $user_id[4];
                foreach ($user_arr as $item){
                    $user = Profile::whereUserId($item)->first();
                    if ($user){
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $rating_levels_id);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id]);
                    }
                }
            }

            $template = RatingTemplate::find($course_rating_level_object->rating_template_id)->toArray();

            $new_template = new RatingTemplate2();
            $new_template->fill($template);
            $new_template->id = $template['id'];
            $new_template->course_rating_level_id = $course_rating_level_id;
            $new_template->course_rating_level_object_id = $course_rating_level_object->id;
            $new_template->course_id = $rating_levels_id;
            $new_template->course_type = 3;
            $new_template->save();

            $categories = RatingCategory::query()->where('template_id', $template['id'])->get()->toArray();
            foreach ($categories as $category){
                $new_category = new RatingCategory2();
                $new_category->fill($category);
                $new_category->id = $category['id'];
                $new_category->course_rating_level_id = $course_rating_level_id;
                $new_category->course_rating_level_object_id = $course_rating_level_object->id;
                $new_category->course_id = $rating_levels_id;
                $new_category->course_type = 3;
                $new_category->save();

                $questions = RatingQuestion::query()->where('category_id', $category['id'])->get()->toArray();
                foreach ($questions as $question){
                    $new_question = new RatingQuestion2();
                    $new_question->fill($question);
                    $new_question->id = $question['id'];
                    $new_question->course_rating_level_id = $course_rating_level_id;
                    $new_question->course_rating_level_object_id = $course_rating_level_object->id;
                    $new_question->course_id = $rating_levels_id;
                    $new_question->course_type = 3;
                    $new_question->save();

                    $answers = RatingQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                    foreach ($answers as $answer){
                        $new_answer = new RatingQuestionAnswer2();
                        $new_answer->fill($answer);
                        $new_answer->id = $answer['id'];
                        $new_answer->course_rating_level_id = $course_rating_level_id;
                        $new_answer->course_rating_level_object_id = $course_rating_level_object->id;
                        $new_answer->course_id = $rating_levels_id;
                        $new_answer->course_type = 3;
                        $new_answer->save();
                    }

                    $answers_matrix = RatingAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                    foreach ($answers_matrix as $answer_matrix){
                        $new_answer_matrix = new RatingAnswerMatrix2();
                        $new_answer_matrix->fill($answer_matrix);
                        $new_answer_matrix->course_rating_level_id = $course_rating_level_id;
                        $new_answer_matrix->course_rating_level_object_id = $course_rating_level_object->id;
                        $new_answer_matrix->course_id = $rating_levels_id;
                        $new_answer_matrix->course_type = 3;
                        $new_answer_matrix->save();
                    }
                }
            }
        }

        CourseRatingLevel::query()
            ->where('id', '=', $course_rating_level_id)
            ->update(['object_rating' => $object_rating]);

        json_result([
            'status' => 'success',
            'message' =>trans('lageneral.successful_save')
        ]);
    }

    public function removeObject($rating_levels_id, $course_rating_level_id, Request $request){
        $ids = $request->ids;

        CourseRatingLevelObject::whereIn('id', $ids)
            ->where('rating_levels_id', '=', $rating_levels_id)
            ->where('course_rating_level_id', '=', $course_rating_level_id)
            ->delete();

        RatingTemplate2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->whereIn('course_rating_level_object_id', $ids)
            ->delete();
        RatingCategory2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->whereIn('course_rating_level_object_id', $ids)
            ->delete();
        RatingQuestion2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->whereIn('course_rating_level_object_id', $ids)
            ->delete();
        RatingQuestionAnswer2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->whereIn('course_rating_level_object_id', $ids)
            ->delete();
        RatingAnswerMatrix2::where('course_rating_level_id', $course_rating_level_id)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', 3)
            ->whereIn('course_rating_level_object_id', $ids)
            ->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    //Ghi danh HV
    public function register($id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $rating_levels = RatingLevels::findOrFail($id);

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        return view('rating::backend.rating_organization_register.index', [
            'rating_levels' => $rating_levels,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'evaluate_training_effectiveness',
        ]);
    }

    public function getDataRegister($id, Request $request) {
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->unit;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = RatingLevelsRegister::query();
        $query->select([
            'a.*',
            'b.code',
            'b.full_name',
            'b.email',
            'b.unit_name',
            'b.parent_unit_name',
            'b.title_name',
        ]);
        $query->from('el_rating_levels_register AS a');
        $query->leftJoin('el_profile_view AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('user as c', 'c.id', '=', 'b.user_id');
        $query->where('a.rating_levels_id', '=', $id);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('c.username', 'like', '%'. $search .'%');
            });
        }
        if ($request->area) {
            $query->leftJoin('el_unit AS u', 'u.code', '=', 'b.unit_code');
            $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($title) {
            $query->where('b.title_id', '=', $title);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.unit_id', $unit_id);
                $sub_query->orWhere('b.unit_id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy('a.user_id', $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {

        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataNotRegister($rating_levels_id, Request $request){
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->unit;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseRegisterView::query();
        $query->select([
            'a.user_id',
            'a.code',
            'a.full_name',
            'a.email',
            'a.unit_name',
            'a.parent_unit_name',
            'a.title_name',
        ]);
        $query->from('el_course_register_view AS a');
        $query->leftJoin('el_rating_levels_courses AS b', function ($sub){
            $sub->on('b.course_id', '=', 'a.course_id');
            $sub->on('b.course_type', '=', 'a.course_type');
        });
        $query->leftJoin('user as c', 'c.id', '=', 'a.user_id');
        $query->where('b.rating_levels_id', '=', $rating_levels_id);
        $query->where('a.user_type', '=', 1);
        $query->where('a.status', '=', 1);
        $query->whereNotIn('a.user_id', function($sub_query) use ($rating_levels_id) {
            $sub_query->select(['user_id']);
            $sub_query->from('el_rating_levels_register');
            $sub_query->where('rating_levels_id', '=', $rating_levels_id);
        });

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('a.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('c.username', 'like', '%'. $search .'%');
            });
        }

        if ($title) {
            $query->where('a.title_id', '=', $title);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('a.unit_id', $unit_id);
                $sub_query->orWhere('a.unit_id', '=', $unit->id);
            });
        }

        $query->groupBy([
            'a.user_id',
            'a.code',
            'a.full_name',
            'a.email',
            'a.unit_name',
            'a.parent_unit_name',
            'a.title_name',
        ]);

        $count = $query->count();
        $query->orderBy('a.user_id', $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function formRegister($id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $rating_levels = RatingLevels::findOrFail($id);
        return view('rating::backend.rating_organization_register.form', [
            'rating_levels' => $rating_levels,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'evaluate_training_effectiveness',
        ]);
    }

    public function saveRegister($rating_levels_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Nhân viên'
        ]);

        $ids = $request->input('ids', null);
        foreach($ids as $id){
            $profile_view = ProfileView::whereUserId($id)->first();

            $model = new RatingLevelsRegister();
            $model->rating_levels_id = $rating_levels_id;
            $model->user_id = $id;
            $model->unit_id = $profile_view->unit_id;
            $model->unit_code = $profile_view->unit_code;

            $model->save();
        }

        json_message(trans('lageneral.successful_save'));
    }

    public function removeRegister($rating_levels_id, Request $request) {
        $ids = $request->input('ids', null);

        RatingLevelsRegister::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    //Kết quả
    public function listReport($rating_levels_id){
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $rating_levels = RatingLevels::find($rating_levels_id);

        return view('rating::backend.rating_organization.list_report', [
            'rating_levels' => $rating_levels,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'evaluate_training_effectiveness',
        ]);
    }

    public function getdataListReport($rating_levels_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseRatingLevel::query();
        $query->select([
            'a.rating_name',
            'a.level',
            'b.id',
            'b.object_type',
            'b.course_rating_level_id',
            'b.user_id',
            'b.start_date',
            'b.end_date'
        ]);
        $query->from('el_course_rating_level as a');
        $query->leftJoin('el_course_rating_level_object as b', 'b.course_rating_level_id', '=', 'a.id');
        $query->where('a.rating_levels_id', '=', $rating_levels_id);
        $query->where('b.rating_levels_id', '=', $rating_levels_id);

        if ($search) {
            $query->where('a.rating_name', 'like', '%'.$search.'%');
        }

        $count = $query->count();
        $query->orderBy('b.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $text_object_type = [
            '1' => 'Học viên',
            '2' => 'Trưởng đơn vị',
            '3' => 'Đồng nghiệp',
            '4' => 'Khác',
        ];

        $rows = $query->get();
        foreach ($rows as $row) {
            $num = 0;

            if ($row->object_type == 1){
                $num += RatingLevelsRegister::where('rating_levels_id', $rating_levels_id)->count();
            }
            if ($row->object_type == 2){
                $num += RatingLevelsRegister::query()->where('rating_levels_id', $rating_levels_id)
                    ->groupBy('unit_id')->count();
            }
            if ($row->object_type == 3){
                $colleague = CourseRatingLevelObjectColleague::query()
                    ->where('course_rating_level_id', '=', $row->course_rating_level_id)
                    ->count('user_id');
                $num += $colleague;
            }
            if ($row->object_type == 4){
                $num += count(explode(',', $row->user_id));
            }

            $row->text_object_type = $text_object_type[$row->object_type];
            $start_date_rating = '';
            $end_date_rating = '';
            if ($row->start_date){
                $start_date_rating = get_date($row->start_date);
            }
            if ($row->end_date){
                $end_date_rating = get_date($row->end_date);
            }

            $row->time_rating = $start_date_rating . ( $end_date_rating ? ' đến ' . $end_date_rating : '');

            $course_rating_level = RatingLevelCourse::query()
                ->where('course_rating_level_id', '=', $row->course_rating_level_id)
                ->where('course_rating_level_object_id', '=', $row->id)
                ->where('course_id', '=', $rating_levels_id)
                ->where('course_type', '=', 3)
                ->where('level', '=', $row->level)
                ->where('send', '=', 1)
                ->count();

            $row->count_user = $course_rating_level . '/' . $num;
            $row->export = route('module.rating_level.report', [$rating_levels_id, 3, $row->course_rating_level_id]).'?course_rating_level_object_id='.$row->id;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getdataListUserRating($rating_levels_id, $course_rating_level_id, Request $request){
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $course_rating_level_object_id = $request->course_rating_level_object_id;
        $prefix = DB::getTablePrefix();
        $object_type_text = [
            '1' => 'Học viên',
            '2' => 'Trưởng đơn vị',
            '3' => 'Đồng nghiệp',
            '4' => 'Khác',
        ];

        $rating_levels = RatingLevels::find($rating_levels_id);
        $course_rating_level = CourseRatingLevel::find($course_rating_level_id);
        $course_rating_level_object = CourseRatingLevelObject::query()
            ->where('id', '=', $course_rating_level_object_id)
            ->where('course_rating_level_id', '=', $course_rating_level_id)
            ->where('rating_levels_id', '=', $rating_levels_id)
            ->first();
        if ($course_rating_level_object->object_type == 4) {
            $user_arr = explode(',', @$course_rating_level_object->user_id);
            $rating_levels_register = RatingLevelsRegister::where('rating_levels_id', $rating_levels_id)->get();

            $num_date = $course_rating_level_object->num_date ? $course_rating_level_object->num_date : 'null';
            $start_date = $course_rating_level_object->start_date ? $course_rating_level_object->start_date : 'null';
            $end_date = $course_rating_level_object->end_date ? $course_rating_level_object->end_date : 'null';

            foreach ($rating_levels_register as $key => $register) {
                $query = Profile::query()
                    ->select([
                        DB::raw($course_rating_level->object_rating . ' as object_rating'),
                        DB::raw($course_rating_level->level . ' as level'),
                        DB::raw($course_rating_level_object->id . ' as id'),
                        DB::raw($course_rating_level_object->object_type . ' as object_type'),
                        DB::raw($course_rating_level_object->time_type . ' as time_type'),
                        DB::raw($num_date . ' as num_date'),
                        DB::raw("'$start_date' as start_date"),
                        DB::raw("'$end_date' as end_date"),
                        DB::raw($register->user_id . ' as user_id'),
                        DB::raw('null as profile_manger_id'),
                        DB::raw('null as object_colleague_id'),
                        'profile.user_id as other_id'
                    ])
                    ->from('el_profile as profile')
                    ->whereIn('profile.user_id', $user_arr);

                $user_other_rating_level[$key] = $query;
                if ($key > 0) {
                    $query->union($user_other_rating_level[$key - 1]);
                    $querySql = $query->toSql();
                    $query = DB::table(DB::raw("($querySql) as a"))->mergeBindings($query->getQuery());
                }
            }
        }
        if ($course_rating_level_object->object_type == 1){
            $query = CourseRatingLevel::query();
            $query->select([
                'rating_level.object_rating',
                'rating_level.level',
                'object.id',
                'object.object_type',
                'object.time_type',
                'object.num_date',
                'object.start_date',
                'object.end_date',
                'register.user_id',
                DB::raw('null as profile_manger_id'),
                DB::raw('null as object_colleague_id'),
                DB::raw('null as other_id'),
            ]);
            $query->from('el_course_rating_level as rating_level');
            $query->leftJoin('el_course_rating_level_object AS object', 'object.course_rating_level_id', '=', 'rating_level.id');
            $query->leftJoin('el_rating_levels_register AS register', 'register.rating_levels_id', '=', 'rating_level.rating_levels_id');
            $query->where('rating_level.rating_levels_id', '=', $rating_levels_id);
            $query->where('rating_level.id', '=', $course_rating_level_id);
            $query->where('object.id', '=', $course_rating_level_object_id);
        }
        if ($course_rating_level_object->object_type == 2){
            $query = CourseRatingLevel::query();
            $query->select([
                'rating_level.object_rating',
                'rating_level.level',
                'object.id',
                'object.object_type',
                'object.time_type',
                'object.num_date',
                'object.start_date',
                'object.end_date',
                'register.user_id',
                'profile_manger.user_id as profile_manger_id',
                DB::raw('null as object_colleague_id'),
                DB::raw('null as other_id'),
            ]);
            $query->from('el_course_rating_level as rating_level');
            $query->leftJoin('el_course_rating_level_object AS object', 'object.course_rating_level_id', '=', 'rating_level.id');
            $query->leftJoin('el_rating_levels_register AS register', 'register.rating_levels_id', '=', 'rating_level.rating_levels_id');
            $query->leftJoin('el_unit as unit', 'unit.code', '=', 'register.unit_code');
            $query->leftJoin('el_unit_manager as manager', function ($sub){
                $sub->on('manager.unit_code', '=', 'unit.code');
                $sub->orOn('manager.unit_code', '=', 'unit.parent_code');
                $sub->where('object.object_type', '=', 2);
            });
            $query->leftJoin('el_profile as profile_manger', 'profile_manger.code', '=', 'manager.user_code');
            $query->where('rating_level.rating_levels_id', '=', $rating_levels_id);
            $query->where('rating_level.id', '=', $course_rating_level_id);
            $query->where('object.id', '=', $course_rating_level_object_id);
        }
        if ($course_rating_level_object->object_type == 3){
            $query = CourseRatingLevel::query();
            $query->select([
                'rating_level.object_rating',
                'rating_level.level',
                'object.id',
                'object.object_type',
                'object.time_type',
                'object.num_date',
                'object.start_date',
                'object.end_date',
                'register.user_id',
                DB::raw('null as profile_manger_id'),
                'object_colleague.user_id as object_colleague_id',
                DB::raw('null as other_id'),
            ]);
            $query->from('el_course_rating_level as rating_level');
            $query->leftJoin('el_course_rating_level_object AS object', 'object.course_rating_level_id', '=', 'rating_level.id');
            $query->leftJoin('el_course_rating_level_object_colleague as object_colleague', function ($sub){
                $sub->on('object_colleague.course_rating_level_id', '=', 'rating_level.id');
                $sub->where('object.object_type', '=', 3);
            });
            $query->leftJoin('el_rating_levels_register AS register', function ($sub){
                $sub->on('object_colleague.rating_user_id', '=', 'register.user_id');
                $sub->on('register.rating_levels_id', '=', 'rating_level.rating_levels_id');
            });
            $query->where('rating_level.rating_levels_id', '=', $rating_levels_id);
            $query->where('rating_level.id', '=', $course_rating_level_id);
            $query->where('object.id', '=', $course_rating_level_object_id);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $start_date_rating = '';
            $end_date_rating = '';
            $rating_status = 0;
            $rating_user = $row->user_id;

            if ($row->object_type == 1){
                $user_id = $row->user_id;
            }
            if ($row->object_type == 2){
                $user_id = $row->profile_manger_id;
            }
            if ($row->object_type == 3){
                $user_id = $row->object_colleague_id;
            }
            if ($row->object_type == 4){
                $user_id = $row->other_id;
            }
            $profile = ProfileView::whereUserId($user_id)->first();
            $profile_rating = ProfileView::whereUserId($rating_user)->first();

            $row->code = $profile->code;
            $row->full_name = $profile->full_name;
            $row->unit_name = $profile->unit_name;
            $row->parent_unit_name = $profile->parent_unit_name;
            $row->object_type = $object_type_text[$row->object_type];
            $row->object_rating = $row->object_rating == 1 ? 'Lớp học' : ($profile_rating->code . ' - '. $profile_rating->full_name);
            $row->rating_level = 'Cấp độ '. $row->level;

            if ($row->time_type == 1){
                $start_date_rating = $row->start_date;
                $end_date_rating = $row->end_date;
            }
            $row->rating_time = get_date($start_date_rating) . ($end_date_rating ? ' đến ' .get_date($end_date_rating) : '');

            $user_rating_level_course = RatingLevelCourse::query()
                ->where('course_rating_level_id', '=', $course_rating_level_id)
                ->where('user_id', '=', $user_id)
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $rating_levels_id)
                ->where('course_type', '=', 3)
                ->where('rating_user', '=', $rating_user)
                ->first();
            if ($user_rating_level_course){
                $rating_status = $user_rating_level_course->send == 1 ? 1 : 2;
            }
            $row->rating_status = $rating_status == 1 ? 'Đã đánh giá' : ($rating_status == 2 ? 'Chưa gửi đánh giá' : 'Chưa đánh giá');
            if ($rating_status == 1){
                $row->export_word = route('module.rating_level.result.export_word', [$rating_levels_id, 3, $user_id, $course_rating_level_id]);
            }
            if ($rating_status == 0){
                $row->result_url = route('module.rating_organization.modal_rating_level', [$rating_levels_id, $course_rating_level_id, $user_id, $rating_user]).'?rating_level_object_id='.$row->id;
            }else{
                $row->result_url = route('module.rating_organization.modal_edit_rating_level', [$rating_levels_id, $course_rating_level_id, $user_id, $rating_user]).'?rating_level_object_id='.$row->id;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function modalRatingLevel($rating_levels_id, $course_rating_level, $user_id, $rating_user, Request $request) {
        $item = RatingLevels::find($rating_levels_id);
        $rating_level = CourseRatingLevel::find($course_rating_level);
        $rating_level_object = CourseRatingLevelObject::find($request->rating_level_object_id);

        $start_date_rating = '';
        $end_date_rating = '';
        if ($rating_level_object){
            if ($rating_level_object->time_type == 1) {
                $start_date_rating = $rating_level_object->start_date;
                $end_date_rating = $rating_level_object->end_date;
            }
        }

        $profile = ProfileView::whereUserId(Auth::id())->first();
        $object_rating = ProfileView::whereUserId($rating_user)->first();
        $template = RatingTemplate2::where('course_rating_level_id', $rating_level->id)
            ->where('course_rating_level_object_id', $request->rating_level_object_id)
            ->where('course_id', $rating_levels_id)
            ->where('course_type', 3)
            ->first();

        return view('rating::backend.rating_organization.modal.rating_level', [
            'item' => $item,
            'course_type' => 3,
            'template' => $template,
            'rating_level' => $rating_level,
            'profile' => $profile,
            'rating_user' => $rating_user,
            'object_rating' => $object_rating,
            'start_date_rating' => $start_date_rating,
            'end_date_rating' => $end_date_rating,
            'rating_level_object_id' => $request->rating_level_object_id,
            'user_id' => $user_id,
        ]);
    }

    public function modalEditRatingLevel($rating_levels_id, $course_rating_level, $user_id, $rating_user, Request $request) {
        $item = RatingLevels::find($rating_levels_id);
        $rating_level = CourseRatingLevel::find($course_rating_level);
        $rating_level_object = CourseRatingLevelObject::find($request->rating_level_object_id);

        $start_date_rating = '';
        $end_date_rating = '';
        if ($rating_level_object){
            if ($rating_level_object->time_type == 1){
                $start_date_rating = $rating_level_object->start_date;
                $end_date_rating = $rating_level_object->end_date;
            }
        }

        $user_type = getUserType();
        $rating_level_course = RatingLevelCourse::query()
            ->where('course_rating_level_id', '=', $course_rating_level)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', $user_type)
            ->where('course_id', '=', $rating_levels_id)
            ->where('course_type', '=', 3)
            ->first();

        $rating_course_categories = RatingLevelCourseCategory::where('rating_level_course_id', '=', $rating_level_course->id)->get();

        $profile = ProfileView::whereUserId(Auth::id())->first();
        $object_rating = ProfileView::whereUserId($rating_user)->first();

        return view('rating::backend.rating_organization.modal.edit_rating_level', [
            'item' => $item,
            'course_type' => 3,
            'rating_course_categories' => $rating_course_categories,
            'rating_level' => $rating_level,
            'profile' => $profile,
            'rating_level_course' => $rating_level_course,
            'rating_user' => $rating_user,
            'object_rating' => $object_rating,
            'start_date_rating' => $start_date_rating,
            'end_date_rating' => $end_date_rating,
            'rating_level_object_id' => $request->rating_level_object_id,
            'user_id' => $user_id,
        ]);
    }

    public function saveRatingCourse($rating_levels_id, $course_rating_level_id, $user_id, $rating_user, Request $request){
        $user_update = getUserId();
        $user_type = getUserType();

        $errors = [];
        $title_report = [];
        $content_report = [];

        $rating_user_id = $request->rating_user_id;
        $level = $request->level;

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

        $answer_matrix_code = $request->answer_matrix_code;

        $model = RatingLevelCourse::firstOrNew(['id' => $rating_user_id]);
        $model->course_rating_level_id = $course_rating_level_id;
        $model->course_rating_level_object_id = $request->rating_level_object_id;
        $model->level = $level;
        $model->user_id = $user_id;
        $model->user_type = $user_type;
        $model->course_id = $rating_levels_id;
        $model->course_type = 3;
        $model->send = $send;
        $model->rating_user = $rating_user;
        $model->user_update = $user_update;
        $model->save();

        foreach($category_id as $cate_key => $cate_id){
            $categories = RatingLevelCourseCategory::firstOrNew(['id' => $user_category_id[$cate_key]]);
            $categories->rating_level_course_id = $model->id;
            $categories->category_id = $cate_id;
            $categories->category_name = $category_name[$cate_id];
            $categories->save();

            if(isset($question_id[$cate_id])){
                foreach($question_id[$cate_id] as $ques_key => $ques_id){
                    $user_ques_id = $user_question_id[$cate_id][$ques_key];
                    $ques_code = $question_code[$cate_id][$ques_id];
                    $ques_name = $question_name[$cate_id][$ques_id];

                    $course_question = RatingLevelCourseQuestion::firstOrNew(['id' => $user_ques_id]);
                    $course_question->course_category_id = $categories->id;
                    $course_question->question_id = $ques_id;
                    $course_question->question_code = isset($ques_code) ? $ques_code : null;
                    $course_question->question_name = $ques_name;
                    $course_question->type = $type[$cate_id][$ques_id];
                    $course_question->multiple = $multiple[$cate_id][$ques_id];
                    $course_question->answer_essay = isset($answer_essay[$cate_id][$ques_id]) ? $answer_essay[$cate_id][$ques_id] : '';
                    $course_question->save();

                    if ($course_question->type == 'choice' && $course_question->multiple == 0){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                    }
                    if ($course_question->type == 'essay' || $course_question->type == 'time'){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                        $content_report[] = isset($course_question->answer_essay) ? $course_question->answer_essay : 'null';
                    }
                    if ($course_question->type == 'dropdown'){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                        $content_report[] = isset($answer_code[$cate_id][$ques_id][$course_question->answer_essay]) ? $answer_code[$cate_id][$ques_id][$course_question->answer_essay] : 'null';
                    }

                    if(isset($answer_id[$cate_id][$ques_id])){
                        if($course_question->type == 'percent'){
                            $total = 0;
                            $arr_answer_percent = $text_answer[$cate_id][$ques_id];
                            foreach ($arr_answer_percent as $percent){
                                $total += preg_replace("/[^0-9]/", '', $percent);
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

                            $course_answer = RatingLevelCourseAnswer::firstOrNew(['id' => $user_ans_id]);
                            $course_answer->course_question_id = $course_question->id;
                            $course_answer->answer_id = $ans_id;
                            $course_answer->answer_code = isset($ans_code) ? $ans_code : '';
                            $course_answer->answer_name = isset($ans_name) ? $ans_name : '';
                            $course_answer->is_text = $text;
                            $course_answer->is_row = $row;

                            if ($course_question->multiple == 1){
                                $course_answer->is_check = isset($is_check[$cate_id][$ques_id][$ans_id]) ? $is_check[$cate_id][$ques_id][$ans_id] : 0;

                                if ($course_question->type == 'choice'){
                                    $title_report[] = isset($ans_code) ? $ans_code : 'null';
                                    $content_report[] = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : (isset($is_check[$cate_id][$ques_id][$ans_id]) ? 1 : 0);
                                }
                            }else{
                                if (isset($is_check[$cate_id][$ques_id]) && ($ans_id == $is_check[$cate_id][$ques_id])){
                                    $course_answer->is_check = $ans_id;

                                    $content_report[] = (isset($ans_code) ? $ans_code : 'null') . (isset($text_answer[$cate_id][$ques_id][$ans_id]) ? ' - '.$text_answer[$cate_id][$ques_id][$ans_id] : '');
                                }else{
                                    $course_answer->is_check = 0;
                                }
                            }

                            if($course_question->type == 'percent'){
                                $course_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) && array_sum($text_answer[$cate_id][$ques_id]) <= 100 ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }else{
                                $course_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }

                            $course_answer->answer_matrix = isset($answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $course_answer->check_answer_matrix = isset($check_answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($check_answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $course_answer->save();

                            if ($course_question->type == 'matrix' && $course_question->multiple == 0 && $course_answer->is_row == 1){
                                $title_report[] = isset($ans_code) ? $ans_code : 'null';

                                $arr_col_answer = RatingQuestionAnswer2::where('course_rating_level_id', $course_rating_level_id)
                                    ->where('course_id', $rating_levels_id)
                                    ->where('course_type', 3)
                                    ->where('question_id', '=', $course_question->question_id)
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
                    }

                    if (in_array($course_question->type, ['text', 'sort', 'percent', 'number'])){
                        $arr_export = RatingLevelCourseAnswer::where('course_question_id', $course_question->id)->get();
                        foreach ($arr_export as $export) {
                            $title_report[] = isset($export->answer_code) ? $export->answer_code : 'null';
                            $content_report[] = isset($export->text_answer) ? $export->text_answer : 'null';
                        }
                    }

                    if (($course_question->type == 'matrix' && $course_question->multiple == 1) || $course_question->type == 'matrix_text'){
                        if(isset($answer_matrix_code[$cate_id][$ques_id])) {
                            foreach ($answer_matrix_code[$cate_id][$ques_id] as $ans_key => $matrix) {

                                $answer_matrix_text = isset($answer_matrix[$cate_id][$ques_id][$ans_key]) ? $answer_matrix[$cate_id][$ques_id][$ans_key] : '';
                                $i = 0;

                                foreach ($matrix as $matrix_key => $matrix_code){
                                    RatingLevelCourseAnswerMatrix::query()
                                        ->updateOrCreate([
                                            'course_question_id' => $course_question->id,
                                            'answer_row_id' => $ans_key,
                                            'answer_col_id' => $matrix_key
                                        ],[
                                            'answer_code' => $matrix_code
                                        ]);

                                    $title_report[] = isset($matrix_code) ? $matrix_code : 'null';

                                    $check = isset($check_answer_matrix[$cate_id][$ques_id][$ans_key]) ? $check_answer_matrix[$cate_id][$ques_id][$ans_key] : [];

                                    if(($course_question->type == 'matrix' && $course_question->multiple == 1)){
                                        $content_report[] = in_array($matrix_key, $check) ? 1 : 0;
                                    }

                                    if($course_question->type == 'matrix_text'){
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

        if ($send == 1){
            if (count($title_report) > 0){
                foreach ($title_report as $key => $title){
                    $export = new RatingLevelCourseExport();
                    $export->course_rating_level_id = $course_rating_level_id;
                    $export->level = $level;
                    $export->user_id = $user_id;
                    $export->user_type = $user_type;
                    $export->course_id = $rating_levels_id;
                    $export->course_type = 3;
                    $export->title = $title;
                    $export->content = isset($content_report[$key]) ? $content_report[$key] : '';
                    $export->save();
                }
            }
        }

        $redirect = route('module.rating_organization.list_report', [$rating_levels_id]);

        if ($send == 1){
            json_result([
                'status' => 'success',
                'message' => 'Đã gửi thành công',
                'redirect' => $redirect,
            ]);
        }else{
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => $redirect,
            ]);
        }
    }

    protected function updateMailUserRatingLevel(array $params, array $user_id, int $object_id){
        $automail = new Automail();
        $automail->template_code = 'action_plan_reminder_01';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $object_id;
        $automail->object_type = 'action_plan_reminder_course_01';
        $automail->addToAutomail();
    }

    protected function updateNotifyUserRatingLevel(array $params, array $user_id){
        $nottify_template = NotifyTemplate::query()->where('code', '=', 'action_plan_reminder_01')->first();
        $subject_notify = $this->mapParams($nottify_template->title, $params);
        $content_notify = $this->mapParams($nottify_template->content, $params);
        $url = $this->getParams($params, 'url');

        $notify = new Notify();
        $notify->subject = $subject_notify;
        $notify->content = $content_notify;
        $notify->url = $url;
        $notify->users = $user_id;
        $notify->addMultiNotify();
    }

    protected function mapParams($content, $params) {
        foreach ($params as $key => $param) {
            if ($key == 'url') {
                $content = str_replace('{'. $key .'}', '<a target="_blank" href="'. $param .'">liên kết này</a>', $content);
            }
            else {
                $content = str_replace('{'. $key .'}', $param, $content);
            }
        }
        return $content;
    }

    protected function getParams($params, $key) {
        if (isset($params->{$key})) {
            return $params->{$key};
        }

        return null;
    }
}
