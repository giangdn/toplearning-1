<?php

namespace Modules\Online\Http\Controllers;

use App\Automail;
use App\Profile;
use App\ProfileView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifyTemplate;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRatingLevel;
use Modules\Offline\Entities\OfflineRatingLevelObject;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRatingLevel;
use Modules\Online\Entities\OnlineRatingLevelObject;
use Modules\Online\Entities\OnlineRatingLevelObjectColleague;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterView;
use Modules\Online\Entities\OnlineResult;
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
use Modules\Rating\Entities\RatingQuestion;
use Modules\Rating\Entities\RatingQuestion2;
use Modules\Rating\Entities\RatingQuestionAnswer;
use Modules\Rating\Entities\RatingQuestionAnswer2;
use Modules\Rating\Entities\RatingTemplate;
use Modules\Rating\Entities\RatingTemplate2;

class RatingLevelController extends Controller
{
    public function index($id, Request $request)
    {

    }

    public function getData($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineRatingLevel::query()
            ->where('course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->rating_template = RatingTemplate::find($row->rating_template_id)->name;
            $row->modal_object_url = route('module.online.rating_level.modal_add_object', ['course_id' => $course_id, 'id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save($course_id, Request $request){
        $this->validateRequest([
            'level' => 'required',
            'rating_template_id' => 'required|exists:el_rating_template,id',
            'rating_name' => 'required',
        ], $request, OnlineRatingLevel::getAttributeName());

        $level = $request->level;
        $rating_template_id = $request->rating_template_id;
        $rating_name = $request->rating_name;

        $check = OnlineRatingLevel::query()
            ->where('course_id', '=', $course_id)
            ->where('level', '=', $level)
            ->where('rating_template_id', '=', $rating_template_id);
        if ($check->exists()){
            json_message('Cấp độ đã chọn mẫu đánh giá', 'error');
        }

        $model = new OnlineRatingLevel();
        $model->course_id = $course_id;
        $model->level = $level;
        $model->rating_template_id = $rating_template_id;
        $model->rating_name = $rating_name;
        $model->save();

        $template = RatingTemplate::find($model->rating_template_id)->toArray();

        $new_template = new RatingTemplate2();
        $new_template->fill($template);
        $new_template->id = $template['id'];
        $new_template->course_rating_level_id = $model->id;
        $new_template->course_id = $model->course_id;
        $new_template->course_type = 1;
        $new_template->save();

        $categories = RatingCategory::query()->where('template_id', $template['id'])->get()->toArray();
        foreach ($categories as $category){
            $new_category = new RatingCategory2();
            $new_category->fill($category);
            $new_category->id = $category['id'];
            $new_category->course_rating_level_id = $model->id;
            $new_category->course_id = $model->course_id;
            $new_category->course_type = 1;
            $new_category->save();

            $questions = RatingQuestion::query()->where('category_id', $category['id'])->get()->toArray();
            foreach ($questions as $question){
                $new_question = new RatingQuestion2();
                $new_question->fill($question);
                $new_question->id = $question['id'];
                $new_question->course_rating_level_id = $model->id;
                $new_question->course_id = $model->course_id;
                $new_question->course_type = 1;
                $new_question->save();

                $answers = RatingQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                foreach ($answers as $answer){
                    $new_answer = new RatingQuestionAnswer2();
                    $new_answer->fill($answer);
                    $new_answer->id = $answer['id'];
                    $new_answer->course_rating_level_id = $model->id;
                    $new_answer->course_id = $model->course_id;
                    $new_answer->course_type = 1;
                    $new_answer->save();
                }

                $answers_matrix = RatingAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                foreach ($answers_matrix as $answer_matrix){
                    $new_answer_matrix = new RatingAnswerMatrix2();
                    $new_answer_matrix->fill($answer_matrix);
                    $new_answer_matrix->course_rating_level_id = $model->id;
                    $new_answer_matrix->course_id = $model->course_id;
                    $new_answer_matrix->course_type = 1;
                    $new_answer_matrix->save();
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.online.edit', ['id' => $course_id])."?tabs=rating_level"
        ]);
    }

    public function remove($course_id, Request $request){
        $ids = $request->ids;

        OnlineRatingLevel::whereIn('id', $ids)->where('course_id', '=', $course_id)->delete();
        OnlineRatingLevelObject::whereIn('online_rating_level_id', $ids)->where('course_id', '=', $course_id)->delete();

        RatingTemplate2::whereIn('course_rating_level_id', $ids)->where('course_id', '=', $course_id)->where('course_type', 1)->delete();
        RatingCategory2::whereIn('course_rating_level_id', $ids)->where('course_id', '=', $course_id)->where('course_type', 1)->delete();
        RatingQuestion2::whereIn('course_rating_level_id', $ids)->where('course_id', '=', $course_id)->where('course_type', 1)->delete();
        RatingQuestionAnswer2::whereIn('course_rating_level_id', $ids)->where('course_id', '=', $course_id)->where('course_type', 1)->delete();
        RatingAnswerMatrix2::whereIn('course_rating_level_id', $ids)->where('course_id', '=', $course_id)->where('course_type', 1)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function modalAddObject($course_id, $id){
        $course = OnlineCourse::find($course_id);
        $online_rating_level = OnlineRatingLevel::find($id);
        $profile = Profile::where('user_id', '>', 2)->get();

        $online_rating_level_object = OnlineRatingLevelObject::query()
            ->where('course_id', '=', $course_id)
            ->where('online_rating_level_id', '=', $id)
            ->get()->toArray();

        $result_object = [];
        foreach ($online_rating_level_object as $object){
            $result_object[$object['object_type']] = $object;
        }

        return view('online::modal.add_object_rating_level', [
            'course' => $course,
            'online_rating_level' => $online_rating_level,
            'profile' => $profile,
            'result_object' => $result_object,
        ]);
    }

    public function getDataObject($course_id, $online_rating_level_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineRatingLevelObject::query()
            ->where('course_id', '=', $course_id)
            ->where('online_rating_level_id', '=', $online_rating_level_id);

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

    public function saveObject($course_id, $online_rating_level_id, Request $request){
        $this->validateRequest([
            'object_type' => 'required',
        ], $request, [
            'object_type' => 'Loại đối tượng',
        ]);

        $rating_level = OnlineRatingLevel::find($online_rating_level_id);

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
            if ($time_type[$item] == 3){
                $course = OnlineCourse::find($course_id);
                if (empty($course->end_date)){
                    json_message('Khóa học không có ngày kết thúc của Đối tượng đánh giá: '. $text_object_type[$item], 'error');
                }
            }

            if ($time_type[$item] == 1){
                if (empty($start_date[$item]) || empty($end_date[$item])){
                    json_message('Khoảng thời gian không thể trống của Đối tượng đánh giá: '. $text_object_type[$item], 'error');
                }

                if (date_convert($end_date[$item]) < date_convert($start_date[$item])){
                    json_message('Khoảng thời gian không hợp lệ của Đối tượng đánh giá: '. $text_object_type[$item], 'error');
                }
            }
        }

        OnlineRatingLevel::query()
            ->where('id', '=', $online_rating_level_id)
            ->update(['object_rating' => $object_rating]);

        OnlineRatingLevelObject::query()
            ->where('course_id', '=', $course_id)
            ->where('online_rating_level_id', '=', $online_rating_level_id)
            ->delete();

        foreach ($object_type as $type) {
            OnlineRatingLevelObject::query()
            ->updateOrCreate([
                'course_id' => $course_id,
                'online_rating_level_id' => $online_rating_level_id,
                'object_type' => $type
            ],[
                'time_type' => isset($time_type[$type]) ? $time_type[$type] : null,
                'start_date' => isset($start_date[$type]) ? date_convert($start_date[$type]) : null,
                'end_date' => isset($end_date[$type]) ? date_convert($end_date[$type], '23:59:59') : null,
                'num_date' => isset($num_date[$type]) ? $num_date[$type] : null,
                'user_id' => isset($user_id[$type]) ? implode(',', $user_id[$type]) : null,
                'rating_user_id' => null,
                'object_view_rating' => isset($object_view_rating[$type]) ? $object_view_rating[$type] : 0,
                'user_completed' => isset($user_completed[$type]) ? $user_completed[$type] : 0,
                'num_user' => isset($num_user[$type]) ? $num_user[$type] : null
            ]);

            if ($type == 1){
                $user_arr = OnlineRegister::whereCourseId($course_id)->whereStatus(1)->pluck('user_id')->toArray();
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

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $course_id);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id]);
                    }
                }
            }
            if ($type == 2){
                $user_arr = DB::query()
                    ->select(['b.user_code'])
                    ->from('el_offline_register_view as a')
                    ->leftJoin('el_unit_manager as b', 'b.unit_code', '=', 'a.unit_code')
                    ->where('a.course_id', $course_id)
                    ->where('a.status',1)
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

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $course_id);
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

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $course_id);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id]);
                    }
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function removeObject($course_id, $online_rating_level_id, Request $request){
        $ids = $request->ids;

        OnlineRatingLevelObject::whereIn('id', $ids)
            ->where('course_id', '=', $course_id)
            ->where('online_rating_level_id', '=', $online_rating_level_id)
            ->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxGetObject($course_id, $online_rating_level_id, Request $request){
        $rating_level_object = OnlineRatingLevelObject::find($request->object_id);

        return json_result([
            'id' => $rating_level_object->id,
            'object_type' => $rating_level_object->object_type,
            'time_type' => $rating_level_object->time_type,
            'num_user' => $rating_level_object->num_user,
            'user_id' => $rating_level_object->user_id ? explode(',', $rating_level_object->user_id) : '',
            'rating_user_id' => $rating_level_object->rating_user_id,
            'start_date' => get_date($rating_level_object->start_date),
            'end_date' => get_date($rating_level_object->end_date),
            'num_date' => $rating_level_object->num_date,
            'object_view_rating' => $rating_level_object->object_view_rating,
            'user_completed' => $rating_level_object->user_completed,
        ]);
    }

    public function listReport($course_id){
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $course = OnlineCourse::find($course_id);
        $page_title = $course->name;

        return view('online::backend.rating_level_result.list_report', [
            'course_id' => $course_id,
            'course' => $course,
            'page_title' => $page_title,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getdataListReport($course_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineRatingLevel::where('course_id', '=', $course_id);

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
                ->where('course_type', '=', 1)
                ->where('level', '=', $row->level)
                ->where('send', '=', 1)
                ->count();

            $row->count_user = $course_rating_level . '/' . $num;
            $row->export = route('module.rating_level.report', [$course_id, 1, $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getdataListUserRating($course_id, $online_rating_level_id, Request $request){
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $prefix = DB::getTablePrefix();
        $object_type_text = [
            '1' => 'Học viên',
            '2' => 'Trưởng đơn vị',
            '3' => 'Đồng nghiệp',
            '4' => 'Khác',
        ];

        $course = OnlineCourse::find($course_id);
        $online_rating_level = OnlineRatingLevel::find($online_rating_level_id);
        $online_rating_level_object_4 = OnlineRatingLevelObject::query()
            ->where('online_rating_level_id', '=', $online_rating_level_id)
            ->where('course_id', '=', $course_id)
            ->where('object_type', '=', 4)
            ->first();
        if ($online_rating_level_object_4){
            $user_arr = $online_rating_level_object_4 ? explode(',', @$online_rating_level_object_4->user_id) : [];
            $online_register = OnlineRegister::whereCourseId($course_id)->whereStatus(1)->get();

            $start_date = $online_rating_level_object_4->start_date ? $online_rating_level_object_4->start_date : 'null';
            $end_date = $online_rating_level_object_4->end_date ? $online_rating_level_object_4->end_date : 'null';

            foreach ($online_register as $register){
                $query = DB::query()
                    ->select([
                        DB::raw(if_empty(@$online_rating_level->object_rating, 'null') .' as object_rating'),
                        DB::raw(if_empty(@$online_rating_level->level, 'null') .' as level'),
                        DB::raw(if_empty(@$online_rating_level_object_4->id, 'null') .' as id'),
                        DB::raw(if_empty(@$online_rating_level_object_4->object_type, 'null') .' as object_type'),
                        DB::raw(if_empty(@$online_rating_level_object_4->time_type, 'null') .' as time_type'),
                        DB::raw(if_empty(@$online_rating_level_object_4->num_date, 'null') .' as num_date'),
                        DB::raw( "'$start_date' as start_date"),
                        DB::raw("'$end_date' as end_date"),
                        DB::raw(if_empty(@$register->user_id, 'null') . ' as user_id'),
                        DB::raw('null as profile_manger_id'),
                        DB::raw('null as object_colleague_id'),
                        'profile.user_id as other_id',
                    ])
                    ->from('el_profile as profile')
                    ->whereIn('profile.user_id', $user_arr);

                $user_other_rating_level[$register->user_id] = $query;
            }
        }

        $query = OnlineRatingLevel::query();
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
            'object_colleague.user_id as object_colleague_id',
            DB::raw('null as other_id'),
        ]);
        $query->from('el_online_rating_level as rating_level');
        $query->leftJoin('el_online_rating_level_object AS object', 'object.online_rating_level_id', '=', 'rating_level.id');
        $query->leftJoin('el_online_register_view AS register', function ($sub){
            $sub->on('register.course_id', '=', 'rating_level.course_id');
        });
        $query->leftJoin('el_unit_manager as manager', function ($sub){
            $sub->on('manager.unit_code', '=', 'register.unit_code');
            $sub->orOn('manager.unit_code', '=', 'register.parent_unit_code');
            $sub->where('object.object_type', '=', 2);
        });
        $query->leftJoin('el_profile as profile_manger', 'profile_manger.code', '=', 'manager.user_code');
        $query->leftJoin('el_online_rating_level_object_colleague as object_colleague', function ($sub){
            $sub->on('object_colleague.online_rating_level_id', '=', 'rating_level.id');
            $sub->on('object_colleague.rating_user_id', '=', 'register.user_id');
            $sub->where('object.object_type', '=', 3);
        });
        $query->where('register.status', '=', 1);
        $query->where('register.user_type', '=', 1);
        $query->where('rating_level.course_id', '=', $course_id);
        $query->where('rating_level.id', '=', $online_rating_level_id);
        $query->where('object.object_type', '!=', 4);

        if ($online_rating_level_object_4){
            foreach ($online_register as $register){
                $query->union($user_other_rating_level[$register->user_id]);
            }

            $querySql = $query->toSql();
            $query = DB::table(DB::raw("($querySql) as a"))->mergeBindings($query->getQuery());
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
            if ($row->time_type == 2){
                if (isset($row->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($course->start_date)) . " +{$row->num_date} day";
                }else{
                    $start_date_rating= $course->start_date;
                }
            }
            if ($row->time_type == 3){
                if (isset($row->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($course->end_date)) . " +{$row->num_date} day";
                }else{
                    $start_date_rating = $course->end_date;
                }
            }
            if ($row->time_type == 4){
                $result = OnlineResult::query()
                    ->where('course_id', '=', $course_id)
                    ->where('user_id', '=', $row->user_id)
                    ->where('result', '=', 1)
                    ->first();
                if ($result){
                    if (isset($row->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$row->num_date} day";
                    }else{
                        $start_date_rating = $result->created_at;
                    }
                }
            }
            $row->rating_time = get_date($start_date_rating) . ($end_date_rating ? ' đến ' .get_date($end_date_rating) : '');

            $user_rating_level_course = RatingLevelCourse::query()
                ->where('course_rating_level_id', '=', $online_rating_level_id)
                ->where('user_id', '=', $user_id)
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $course_id)
                ->where('course_type', '=', 1)
                ->where('rating_user', '=', $row->user_id)
                ->first();
            if ($user_rating_level_course){
                $rating_status = $user_rating_level_course->send == 1 ? 1 : 2;
            }
            $row->rating_status = $rating_status == 1 ? 'Đã đánh giá' : ($rating_status == 2 ? 'Chưa gửi đánh giá' : 'Chưa đánh giá');
            if ($rating_status == 1){
                $row->export_word = route('module.rating_level.result.export_word', [$course_id, 1, $user_id, $online_rating_level_id]);
            }
            if ($rating_status == 0){
                $row->result_url = route('module.online.rating_level.modal_rating_level', [$course_id, $online_rating_level_id, $user_id, $row->user_id]).'?rating_level_object_id='.$row->id;
            }else{
                $row->result_url = route('module.online.rating_level.modal_edit_rating_level', [$course_id, $online_rating_level_id, $user_id, $row->user_id]).'?rating_level_object_id='.$row->id;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function modalRatingLevel($course_id, $course_rating_level, $user_id, $rating_user, Request $request) {
        $item = OnlineCourse::find($course_id);
        $rating_level = OnlineRatingLevel::find($course_rating_level);
        $rating_level_object = OnlineRatingLevelObject::find($request->rating_level_object_id);
        $result = OnlineResult::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', $rating_user)
            ->where('result', '=', 1)
            ->first();

        $start_date_rating = '';
        $end_date_rating = '';
        if ($rating_level_object){
            if ($rating_level_object->time_type == 1){
                $start_date_rating = $rating_level_object->start_date;
                $end_date_rating = $rating_level_object->end_date;
            }
            if ($rating_level_object->time_type == 2){
                if (isset($rating_level_object->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($item->start_date)) . " +{$rating_level_object->num_date} day";
                }else{
                    $start_date_rating= $item->start_date;
                }
            }
            if ($rating_level_object->time_type == 3){
                if (isset($rating_level_object->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($item->end_date)) . " +{$rating_level_object->num_date} day";
                }else{
                    $start_date_rating = $item->end_date;
                }
            }
            if ($rating_level_object->time_type == 4){
                if ($result){
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating = $result->created_at;
                    }
                }
            }
        }

        $profile = ProfileView::whereUserId(Auth::id())->first();
        $object_rating = ProfileView::whereUserId($rating_user)->first();
        $template = RatingTemplate2::where('course_rating_level_id', $rating_level->id)
            ->where('course_id', $course_id)
            ->where('course_type', 1)
            ->first();

        return view('online::modal.rating_level', [
            'item' => $item,
            'course_type' => 1,
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

    public function modalEditRatingLevel($course_id, $course_rating_level, $user_id, $rating_user, Request $request) {
        $item = OnlineCourse::find($course_id);
        $rating_level = OnlineRatingLevel::find($course_rating_level);
        $rating_level_object = OnlineRatingLevelObject::find($request->rating_level_object_id);
        $result = OnlineResult::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', $rating_user)
            ->where('result', '=', 1)
            ->first();

        $start_date_rating = '';
        $end_date_rating = '';
        if ($rating_level_object){
            if ($rating_level_object->time_type == 1){
                $start_date_rating = $rating_level_object->start_date;
                $end_date_rating = $rating_level_object->end_date;
            }
            if ($rating_level_object->time_type == 2){
                if (isset($rating_level_object->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($item->start_date)) . " +{$rating_level_object->num_date} day";
                }else{
                    $start_date_rating= $item->start_date;
                }
            }
            if ($rating_level_object->time_type == 3){
                if (isset($rating_level_object->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($item->end_date)) . " +{$rating_level_object->num_date} day";
                }else{
                    $start_date_rating = $item->end_date;
                }
            }
            if ($rating_level_object->time_type == 4){
                if ($result){
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating = $result->created_at;
                    }
                }
            }
        }

        $user_type = getUserType();
        $rating_level_course = RatingLevelCourse::query()
            ->where('course_rating_level_id', '=', $course_rating_level)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', $user_type)
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', 1)
            ->first();

        $rating_course_categories = RatingLevelCourseCategory::where('rating_level_course_id', '=', $rating_level_course->id)->get();

        $profile = ProfileView::whereUserId(Auth::id())->first();
        $object_rating = ProfileView::whereUserId($rating_user)->first();

        return view('online::modal.edit_rating_level', [
            'item' => $item,
            'course_type' => 1,
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

    public function saveRatingCourse($course_id, $course_rating_level_id, $user_id, $rating_user, Request $request){
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
        $model->level = $level;
        $model->user_id = $user_id;
        $model->user_type = $user_type;
        $model->course_id = $course_id;
        $model->course_type = 1;
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
                                    ->where('course_id', $course_id)
                                    ->where('course_type', 1)
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
                    $export->course_id = $course_id;
                    $export->course_type = 1;
                    $export->title = $title;
                    $export->content = isset($content_report[$key]) ? $content_report[$key] : '';
                    $export->save();
                }
            }
        }

        $redirect = route('module.online.rating_level.list_report', [$course_id]);

        if ($send == 1){
            json_result([
                'status' => 'success',
                'message' => 'Đã gửi thành công',
                'redirect' => $redirect,
            ]);
        }else{
            json_result([
                'status' => 'success',
                'message' =>trans('lageneral.successful_save'),
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
        $automail->object_type = 'action_plan_reminder_online_01';
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
