<?php

namespace App\Http\Controllers\Mobile;

use App\Profile;
use App\Scopes\CompanyScope;
use App\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineComment;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRating;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Rating\Entities\RatingCourse;

class OfflineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $items = $this->getItems($request);
        return view('themes.mobile.frontend.offline_course.index', [
            'items' => $items,
        ]);
    }

    public function getItems(Request $request) {
        OfflineCourse::addGlobalScope(new CompanyScope());
        $query = OfflineCourse::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        $query->orderByDesc('id');
        $items = $query->paginate(12);
        $items->appends($request->query());

        return $items;
    }

    public function detail($id){
        OfflineCourse::updateItemViews($id);
        $user_id = Auth::id();
        $item = OfflineCourse::where('id', '=', $id)
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->firstOrFail();

        $register = OfflineRegister::where('user_id', '=', $user_id)
            ->where('course_id', '=', $id)
            ->where('status', '=', 1)
            ->first();

        $categories = OfflineCourse::getCourseCategory($item->training_program_id, $item->id);

        $comments = OfflineComment::where('course_id', '=', $id)->get();
        $profile = Profile::where('user_id', '=', $user_id)->first();
        $rating_course = RatingCourse::query()
            ->where('course_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', 2)
            ->first();

        $indem = Indemnify::query()
            ->where('user_id', '=', $user_id)
            ->where('course_id', '=', $id)
            ->first();

        $sliders = Slider::where('status', '=', 1)
            ->where('location', '=', 'online')
            ->get();

        $rating_star = OfflineRating::where('course_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->first();
        $text_status = function ($status) {
            return OfflineCourse::getStatusRegisterText($status);
        };
        $class_status = function ($status) {
            return OfflineCourse::getBtnClassStatusRegister($status);
        };
        $course_time = preg_replace("/[^0-9]./", '', $item->course_time);
        $course_time_unit = preg_replace("/[^a-z]/", '', $item->course_time);

        return view('themes.mobile.frontend.offline_course.detail', [
            'item' => $item,
            'categories' => $categories,
            'comments' => $comments,
            'profile' => $profile,
            'rating_course' => $rating_course,
            'sliders' => $sliders,
            'rating_star' => $rating_star,
            'text_status' => $text_status,
            'class_status' => $class_status,
            'register' => $register,
            'course_time' => $course_time,
            'course_time_unit' => $course_time_unit,
            'indem' => $indem
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

        $model = new OfflineComment();
        $model->course_id = $id;
        $model->user_id = Auth::id();
        $model->content = $request->post('content');

        if ($model->save()) {
            return redirect()->route('themes.mobile.frontend.offline.detail', ['course_id' => $id]);
        }
    }

    public function checkPDF(Request $request){
        $path = explode('uploads/', $request->path);
        $file = explode('/', $path[1]);
        if (isFilePdf($file[3])){
            json_result([
                'status' => 'success',
                'path' => $request->path
            ]);
        }

        json_result([
            'status' => 'error'
        ]);
    }
    public function viewPDF(Request $request){
        $path = $request->path;
        $path = str_replace(config('app.url'), config('app.mobile_url'), $path);

        return view('themes.mobile.frontend.offline_course.view_pdf', [
            'path' => $path,
        ]);
    }
}
