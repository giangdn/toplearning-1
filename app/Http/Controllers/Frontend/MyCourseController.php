<?php

namespace App\Http\Controllers\Frontend;

use App\PlanAppStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $query = \DB::table('el_course_view as a')
            ->select(['a.*','c.status as plan_app_status','c.start_date as start_evaluation'])
            ->join('el_course_register_view as b',function($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->leftJoin('el_plan_app as c',function ($join){
                $join->on('c.course_id', '=', 'a.id');
                $join->on('c.course_type', '=', 'a.course_type');
                $join->on('c.user_id', '=', 'b.user_id');
            })
            ->where('b.user_id','=', \Auth::id())
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.isopen', '=', 1);

        $query->orderBy('id', 'desc');
        $rows = $query->paginate(20);

        foreach ($rows as $row) {
            $now = date('Y-m-d');
            $date = $this->calDate($row->start_evaluation, $now);

            $row->evaluation = $this->isEvaluation($row->start_evaluation, $row->plan_app_status);
            if ($row->plan_app_status == 2){
                $row->time_evaluation = ($date <= 0) ? 0 : $date .' ngày';
            }else{
                $row->time_evaluation = '';
            }
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->status_text = PlanAppStatus::getStatus($row->plan_app_status);
            $row->course_url =$row->course_type==1?route('module.online.detail',['id'=>$row->course_id]):route('module.offline.detail',['id'=>$row->course_id]);
        }

        return view('frontend.my_course', [
            'items' => $rows,
        ]);
    }

    public function getData(Request $request)
    {
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = \DB::table('el_course_view as a')
            ->join('el_course_register_view as b',function($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->leftJoin('el_plan_app as c',function ($join){
                $join->on( 'c.course_id','=','a.course_id');
                $join->on( 'c.course_type','=','a.course_type');
                $join->on( 'c.user_id','=','b.user_id');
            })
            ->where('b.user_id','=',Auth::id())
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.isopen', '=', 1)
            ->select(['a.*','c.status as plan_app_status','c.start_date as start_evaluation']);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $now = date('Y-m-d');
            $date = $this->calDate($row->start_evaluation, $now);

            $row->evaluation = $this->isEvaluation($row->start_evaluation, $row->plan_app_status);
            if ($row->plan_app_status == 2){
                $row->time_evaluation = ($date <= 0) ? 0 : $date .' ngày';
            }else{
                $row->time_evaluation = '';
            }
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->status_text =PlanAppStatus::getStatus($row->plan_app_status);
            $row->course_url =$row->course_type==1?route('module.online.detail',['course_id'=>$row->course_id]):route('module.offline.detail',['course_id'=>$row->course_id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    protected function calDate($date1, $date2) {
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        $total_date = $years*365 + $months*30 + $days;

        return number_format($total_date, 2);
    }

    private function isEvaluation($start_evaluation, $status)
    {
        $days = (strtotime(date('Y-m-d')) - strtotime($start_evaluation))/ (60 * 60 * 24);

        if ($days>=0 && $days<=8)
            return 1; // đánh giá
        if ($days>8)
            return 2; // hết hạn đánh giá
        return 0;
    }
}
