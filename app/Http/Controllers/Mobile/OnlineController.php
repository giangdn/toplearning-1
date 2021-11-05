<?php

namespace App\Http\Controllers\Mobile;

use App\Profile;
use App\ProfileView;
use App\Scopes\CompanyScope;
use App\UserViewCourse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Capabilities\Entities\CapabilitiesResult;
use Modules\Online\Entities\OnlineComment;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseAskAnswer;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseNote;
use Modules\Online\Entities\OnlineResult;
use Modules\Rating\Entities\RatingCourse;
use Modules\Online\Entities\OnlineCourseLesson;
use App\Models\Categories\Subject;
use Modules\User\Entities\TrainingProcess;
use Modules\Online\Entities\OnlineRegister;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Online\Entities\OnlineCourseActivity;

class OnlineController extends Controller
{
    public function index(Request $request){
        $items = $this->getItems($request);
        $lay = 'online';
        return view('themes.mobile.frontend.online_course.index', [
            'items' => $items,
            'lay' => $lay
        ]);
    }

    public function getItems(Request $request) {
        $type = $request->type;

        OnlineCourse::addGlobalScope(new CompanyScope());
        $query = OnlineCourse::query();
        if ($type && $type == 5){
            $last_review = CapabilitiesResult::getLastReviewUser(\Auth::id());
            $query->leftJoin('el_capabilities_title_subject AS title_subject', 'title_subject.subject_id', '=', 'el_online_course.subject_id')
                ->leftJoin('el_capabilities_review_detail AS review_detail', 'review_detail.captitle_id', '=', 'title_subject.capabilities_title_id')
                ->where('review_detail.review_id', '=', $last_review->id)
                ->whereColumn('review_detail.practical_level', '<', 'review_detail.standard_level')
                ->whereColumn('review_detail.practical_level', '<=', 'title_subject.level')
                ->whereColumn('review_detail.standard_level', '>=', 'title_subject.level');
        }
        if ($type && $type == 3){
            $query->leftJoin('el_online_register', 'el_online_register.course_id', '=', 'el_online_course.id')
                ->where('el_online_register.user_id', '=', \Auth::id())
                ->where('el_online_register.status', '=', 1)
                ->whereNotExists(function ($subquery) {
                    $subquery->select(['id'])
                        ->from('el_online_result')
                        ->whereColumn('register_id', '=', 'el_online_register.id')
                        ->where('result', '=', 1);
                })
                ->where(function ($sub){
                    $sub->whereNull('el_online_course.end_date');
                    $sub->orWhere('el_online_course.end_date', '>', date('Y-m-d'));
                })
                ->where('el_online_course.start_date', '<', date('Y-m-d'));
        }
        if ($type && $type == 1){
            $query->where(function ($sub){
                $sub->whereNull('end_date');
                $sub->orWhere('end_date', '>', date('Y-m-d'));
            })
                ->where('start_date', '<', date('Y-m-d'));
        }
        if ($type && $type == 2){
            $query->where('start_date', '>', date('Y-m-d'));
        }
        if ($type && $type == 4){
            $query->where(\DB::raw('month(start_date)'), '=', date('m'));
        }
        $query->where('el_online_course.status', '=', 1);
        $query->where('el_online_course.isopen', '=', 1);

        if (Profile::usertype() == 2){
            $query->orderBy('el_online_course.id');

            $items = $query->limit(3)->get();
        }else{
            $query->orderByDesc('el_online_course.id');

            $items = $query->paginate(12);
            $items->appends($request->query());

        }

        return $items;
    }

    public function detail($id){
        OnlineCourse::updateItemViews($id);
        $item = OnlineCourse::where('id', '=', $id)
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->firstOrFail();

        $course_time = preg_replace("/[^0-9]./", '', $item->course_time);
        $course_time_unit = preg_replace("/[^a-z]/", '', $item->course_time);
        $comments = OnlineComment::where('course_id', '=', $id)
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
        $rating_course = RatingCourse::where('course_id', '=', $id)
            ->where('user_id', '=', Auth::id())
            ->where('type', '=', 1)
            ->first();

        $user_id = Auth::id();
        if($item->auto == 2) {
            $this->autoRegisterCourse($user_id, $id);
        }
        $get_activity_courses = OnlineCourseActivity::where('course_id',$id)->get();
        $lessons_course = OnlineCourseLesson::where('course_id',$id)->get();
        $time_user_view_course = UserViewCourse::updateOrCreate([
            'course_id' => $id,
            'course_type' => 1,
            'user_id' => $user_id,
        ], [
            'course_id' => $id,
            'course_type' => 1,
            'user_id' => $user_id,
            'time_view' => date('Y-m-d H:i'),
        ]);
        $get_result = OnlineResult::where('course_id',$id)->where('user_id',$user_id)->first();
        $check_register = OnlineRegister::where('course_id',$id)->where('user_id',$user_id)->first();
        $date_join = OnlineCourseActivityHistory::select('created_at')->where('course_id',$id)->where('user_id',$user_id)->first();
        $condition_activity = OnlineCourseCondition::where('course_id',$id)->first();
        if(!empty($condition_activity)) {
            $condition_activity = explode(',',$condition_activity->activity);
        }
        $ask_answer = OnlineCourseAskAnswer::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
            'c.name',
        ])
            ->from('el_online_course_ask_answer AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id_ask')
                    ->where('a.user_type_ask', '=', 1);
            })
            ->leftJoin('el_quiz_user_secondary AS c', function ($sub){
                $sub->on('c.id', '=', 'a.user_id_ask')
                    ->where('a.user_type_ask', '=', 2);
            })
            ->where('a.course_id', '=', $id)
            ->where('a.user_id_ask', '=', getUserId())
            ->where('a.user_type_ask', '=', getUserType())
            ->where('a.status', '=', 1)
            ->orderBy('a.id', 'desc')
            ->paginate(5);

        $notes = OnlineCourseNote::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
            'c.name',
        ])
            ->from('el_online_course_note AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 1);
            })
            ->leftJoin('el_quiz_user_secondary AS c', function ($sub){
                $sub->on('c.id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 2);
            })
            ->where('a.course_id', '=', $id)
            ->where('a.user_id', '=', getUserId())
            ->where('a.user_type', '=', getUserType())
            ->orderBy('a.id', 'desc')
            ->paginate(5);

        $profile = ProfileView::where('user_id', '=', $user_id)->first();

        return view('themes.mobile.frontend.online_course.detail', [
            'item' => $item,
            'activities' => $item->getActivities(),
            'course_time' => $course_time,
            'course_time_unit' => $course_time_unit,
            'comments' => $comments,
            'rating_course' => $rating_course,
            'lessons_course' => $lessons_course,
            'get_activity_courses' => $get_activity_courses,
            'time_user_view_course' => $time_user_view_course,
            'get_result' => $get_result,
            'check_register' => $check_register,
            'date_join' => $date_join,
            'condition_activity' => $condition_activity,
            'ask_answer' => $ask_answer,
            'notes' => $notes,
            'profile' => $profile,
        ]);
    }

    public function comment($id, Request $request){
        $this->validateRequest([
            'content' => 'required',
        ], $request, ['content' => 'Nội dung']);

        $content = strtolower($request->post('content'));

        if (strpos($content, 'sex') !== false || strpos($content, 'xxx') !== false || strpos($content, 'địt') !== false){
            return response()->json([
                'message' => 'Nội dung có từ phản cảm',
                'status' => 'warning',
            ]);
        }

        $model = new OnlineComment();
        $model->course_id = $id;
        $model->user_id = Auth::id();
        $model->content = $request->post('content');
        $model->save();

        return response()->json([
            'message' => 'Lưu thành công',
            'status' => 'success',
            'redirect' => route('themes.mobile.frontend.online.detail', ['course_id' => $id]),
        ]);
    }

    public function ask_answer($id, Request $request) {
        $this->validateRequest([
            'ask_content' => 'required|string|max:1000',
        ], $request, ['ask_content' => 'Nội dung không được để trống']);

        $ask_content = $request->ask_content;
        if (strpos($ask_content, 'sex') !== false || strpos($ask_content, 'xxx') !== false || strpos($ask_content, 'địt') !== false){
            return response()->json([
                'message' => 'Nội dung có từ phản cảm',
                'status' => 'warning',
            ]);
        }

        $model = new OnlineCourseAskAnswer();
        $model->course_id = $id;
        $model->user_id_ask = getUserId();
        $model->user_type_ask = getUserType();
        $model->ask = $ask_content;
        $model->save();

        return response()->json([
            'message' => 'Lưu thành công',
            'status' => 'success',
            'redirect' => route('themes.mobile.frontend.online.detail', ['course_id' => $id]),
        ]);
    }

    public function note($id, Request $request) {
        $this->validateRequest([
            'note_content' => 'required|string|max:1000',
        ], $request, ['note_content' => 'Nội dung không được để trống']);

        $note_content = $request->note_content;
        if (strpos($note_content, 'sex') !== false || strpos($note_content, 'xxx') !== false || strpos($note_content, 'địt') !== false){
            return response()->json([
                'message' => 'Nội dung có từ phản cảm',
                'status' => 'warning',
            ]);
        }

        $model = new OnlineCourseNote();
        $model->course_id = $id;
        $model->user_id = getUserId();
        $model->user_type = getUserType();
        $model->note = $note_content;
        $model->save();

        return response()->json([
            'message' => 'Lưu thành công',
            'status' => 'success',
            'redirect' => route('themes.mobile.frontend.online.detail', ['course_id' => $id]),
        ]);
    }

    public function viewScorm($course_id, $activity_id, $attempt_id, Request $request){
        $title = $request->title;

        $course = OnlineCourse::findOrFail($course_id);
        $activity = OnlineCourseActivityScorm::findOrFail($activity_id);
        $attempt = $activity->attempts()
            ->where('id', $attempt_id)
            ->firstOrFail(['id', 'suspend_data']);

        return view('themes.mobile.frontend.online_course.scorm.player', [
            'course' => $course,
            'activity' => $activity,
            'attempt' => $attempt,
            'user' => Auth::user(),
            'title' => $title,
        ]);

        /*return view('themes.mobile.frontend.online_course.scorm.player', [
            'course_id' => $course_id,
            'activity_id' => $activity_id,
            'attempt_id' => $attempt_id,
            'title' => $title
        ]);*/
    }

    public function autoRegisterCourse($user_id, $course_id) {
        $course = OnlineCourse::findOrFail($course_id);
        $subject = Subject::findOrFail($course->subject_id);
        $profile = \DB::table('el_profile_view')->where('user_id','=',$user_id)->first();
        // dd($profile->code);
        TrainingProcess::updateOrCreate(
            [
                'user_id'=>$user_id,
                'course_id'=>$course_id,
                'course_type'=>1
            ],
            [
                'course_code'=>$course->code,
                'course_name'=>$course->name,
                'subject_id'=>$subject->id,
                'subject_code'=>$subject->code,
                'subject_name'=>$subject->name,
                'titles_code'=>$profile->title_code,
                'titles_name'=>$profile->title_name,
                'unit_code'=>$profile->unit_code,
                'unit_name'=>$profile->unit_name,
                'start_date'=>$course->start_date,
                'end_date'=>$course->end_date,
                'process_type'=>1,
                'certificate'=>$course->cert_code,
            ]
        );
        $model = OnlineRegister::firstOrNew(['user_id' => $user_id, 'course_id' => $course_id]);
        $model->user_id = $user_id;
        $model->course_id = $course_id;
        $model->status = 1;
        $quizs = Quiz::where('course_id', '=', $course_id)->where('status', '=', 1)->get();
        if ($quizs){
            foreach ($quizs as $quiz){
                $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                if ($quiz_part){
                    $query = QuizRegister::where('quiz_id', '=', $quiz->id)
                        ->where('user_id', '=', $user_id)
                        ->where('type', '=', 1);
                    if ($query->exists()) {
                        $query->update([
                            'part_id' => $quiz_part->id
                        ]);
                    }else {
                        $query->insert([
                            'quiz_id' => $quiz->id,
                            'user_id' => $user_id,
                            'part_id' => $quiz_part->id,
                            'type' => 1,
                        ]);
                    }
                }else{
                    continue;
                }
            }
        }
        $model->save();
        return;
    }
}
