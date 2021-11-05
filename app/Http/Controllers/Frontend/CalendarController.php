<?php

namespace App\Http\Controllers\Frontend;

use App\CourseView;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\CourseRegisterView;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (url_mobile()){
            return view('themes.mobile.frontend.calendar');
        }
        return view('frontend.calendar');
    }

    public function calendarWeek(Request $request)
    {
        $type = $request->type ? $request->type : 1;
        $list_course = $this->getAllCourse($type);

        $dt = now();
        if (isset($request->year) && isset($request->week)) {
            $dt->setISODate($request->year, $request->week);
        } else {
            $dt->setISODate($dt->format('o'), $dt->format('W'));
        }
        $year = $dt->format('o');
        $week = $dt->format('W');

        return view('frontend.calendar_week', [
            'list_course' => $list_course,
            'week' => $week,
            'dt' => $dt,
            'year' => $year,
        ]);
    }

    public function getData(Request $request)
    {
        $type = $request->type;
        $user_id = Auth::id();
        $result = [];
        $allCourse = $this->getAllCourse($type);

        foreach ($allCourse as $item){
            // dd($item);
            $check_register = CourseRegisterView::where('course_id',$item->course_id)->where('course_type',$item->course_type)->where('status',1)->where('user_id',$user_id)->first();
            if (url_mobile()) {
                $url = ($item->course_type == 1) ? route('themes.mobile.frontend.online.detail', ['course_id' => $item->id]) : route('themes.mobile.frontend.offline.detail', ['course_id' => $item->id]);
            }else{
                if(!empty($check_register)) {
                    $url = ($item->course_type == 1) ? route('module.online.detail_online', ['id' => $item->course_id]) : route('module.offline.detail', ['id' => $item->course_id]);
                } else {
                    $url = route('frontend.all_course', ['type' => $item->course_type, 'course_id' => $item->course_id]) ;
                }
            }

            $result[] = [
                'title' => $item->name,
                'start' => get_date($item->start_date, 'Y-m-d'),
                'end' => ($item->end_date ? Carbon::parse($item->end_date)->addDay(1)->format('Y-m-d') : ''),
                'url' => $url,
                'description' => $item->name . ' (' . $item->code .')'. PHP_EOL . get_date($item->start_date) . ($item->end_date ? ' - '. get_date($item->end_date) : ''),
            ];

        }

        return response()->json($result);
    }

    public function getAllCourse($type)
    {
        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->select(['el_course_view.*']);
        if ($type == 1){
            $query->leftJoin('el_course_register_view as b', function ($join){
                $join->on('el_course_view.course_id','=','b.course_id');
                $join->on('el_course_view.course_type','=','b.course_type');
            });
            $query->where('b.user_id', '=', Auth::id());
        } else if ($type == 2) {
            $query->where('el_course_view.course_type',1);
        } else {
            $query->where('el_course_view.course_type',2);
        }
        $query->where('el_course_view.status', '=', 1)
            ->where('el_course_view.isopen', '=', 1);
        // dd($query->get());
        return $query->get();
    }
}
