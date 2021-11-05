<?php

namespace Modules\Online\Http\Controllers;

use App\Helpers\VideoStream;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseLesson;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;

class EmbedUrlController extends Controller
{
    public function index($course_id,$lesson, Request $request) {
        $url = $request->get('url');
        // dd($url);
        $title = $request->get('title');
        //$get_lesson_activities = OnlineCourseActivity::where('lesson_id',$lesson)->get();
        $item = OnlineCourse::find($course_id);
        $lessons_course = OnlineCourseLesson::where('course_id',$course_id)->get();

        $part = function ($subject_id){
            $user_type = Quiz::getUserType();
            $item = QuizPart::where('quiz_id', '=', $subject_id)
                ->whereIn('id', function ($subquery) use ($user_type, $subject_id) {
                    $subquery->select(['a.part_id'])
                        ->from('el_quiz_register AS a')
                        ->join('el_quiz_part AS b', 'b.id', '=', 'a.part_id')
                        ->where('a.quiz_id', '=', $subject_id)
                        ->where('a.user_id', '=', getUserId())
                        ->where('a.type', '=', $user_type)
                        ->where(function ($where){
                            $where->orWhere('b.end_date', '>', date('Y-m-d H:i:s'));
                            $where->orWhereNull('b.end_date');
                        });
                })->first();
            return $item;
        };

        if (is_youtube_url($url)) {
            $url = 'https://www.youtube.com/embed/' . get_youtube_id($url);
        }

        if(url_mobile())
            $view = "themes.mobile.frontend.online_course.embed";
        else
            $view = "online::frontend.embed";

        return view($view, [
            'url' => $url,
            'title' => $title,
            'course_id' => $course_id,
            'item' => $item,
            //'get_lesson_activities' => $get_lesson_activities,
            'part' => $part,
            'lesson_course' => $lesson,
            'lessons_course' => $lessons_course
        ]);
    }
}
