<?php

namespace App\Http\Controllers\Backend;

use App\Exports\IHRPTemplate1Export;
use App\Exports\IHRPTemplate2Export;
use App\Exports\IHRPTemplate3Export;
use App\Profile;
use App\Models\Categories\TrainingForm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineResult;

class IHRPController extends Controller
{
    public function index() {
        return view('ihrp.index');
    }
    public function template1(){
        return view('ihrp.template1.index');
    }

    public function getdataTemplate1(Request $request){

        $start_date = date_convert($request->input('start_date'));
        $end_date = date_convert($request->input('end_date'), '23:59:59');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = \DB::query();
        $query->from('el_course_view');
        $query->where('status', '=', 1);

        if ($start_date && $end_date) {
            $query->where('start_date', '>=', $start_date);
            $query->where('start_date', '<=', $end_date);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row){
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->used = 1;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function template2(){
        return view('ihrp.template2.index');
    }

    public function getdataTemplate2(Request $request){

        $start_date = date_convert($request->input('start_date'));
        $end_date = date_convert($request->input('end_date'), '23:59:59');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = \DB::query();
        $query->from('el_course_view');
        $query->where('status', '=', 1);
        if ($start_date && $end_date) {
            $query->where('start_date', '>=', $start_date);
            $query->where('start_date', '<=', $end_date);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row){
            if ($row->course_type == 2){
                $teachers = $this->getTeacher($row->id);
                $off = OfflineCourse::find($row->id);
                $training_form = TrainingForm::find($off->training_form_id);

                $register_id = OfflineRegister::where('course_id', '=', $row->id)->pluck('id')->toArray();
                $student_cost = OfflineStudentCost::whereIn('register_id', $register_id)->sum('cost');
                $course_cost = OfflineCourseCost::where('course_id', '=', $row->id)->sum('actual_amount');

                $off_cost = $course_cost + $student_cost;

            }else{
                $onl_cost = OnlineCourseCost::where('course_id', '=', $row->id)->sum('actual_amount');
            }

            $row->teachers = $row->course_type == 2 ? $teachers : '';
            $row->training_form = $row->course_type == 2 ? $training_form->name : '';
            $row->course_cost = $row->course_type == 2 ? number_format($off_cost, 0) : number_format($onl_cost, 0);
            $row->training_plan = 0;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function template3(){
        return view('ihrp.template3.index');
    }

    public function getdataTemplate3(Request $request){

        $start_date = date_convert($request->input('start_date'));
        $end_date = date_convert($request->input('end_date'), '23:59:59');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = \DB::query();
        $query->from('el_course_register_view as a');
        $query->leftJoin('el_course_view as b', function ($sub){
            $sub->on('b.course_id', '=', 'a.course_id')->whereColumn('b.course_type', '=', 'a.course_type');
        });
        $query->where('a.status', '=', 1);
        $query->where('b.status', '=', 1);

        if ($start_date && $end_date) {
            $query->where('b.start_date', '>=', $start_date);
            $query->where('b.start_date', '<=', $end_date);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get(['a.user_id', 'a.course_id', 'a.course_type']);
        foreach ($rows as $row){
            if ($row->course_type == 1){
                $course_onl = OnlineCourse::find($row->course_id);
                $user_onl = Profile::find($row->user_id);
                $result = OnlineResult::get();
                if (!empty($result)){
                    $result_onl = OnlineResult::where('user_id', '=', $row->user_id)
                        ->where('course_id', '=', $row->course_id)->get(['result']);
                }
            }else{
                $course_off = OfflineCourse::find($row->course_id);
                $user_off = Profile::find($row->user_id);
                $result = OfflineResult::get();
                if (!empty($result)) {
                    $result_off = OfflineResult::where('user_id', '=', $row->user_id)
                        ->where('course_id', '=', $row->course_id)->get(['result']);
                }
            }

            $row->course_code = $row->course_type == 1 ? $course_onl->code : $course_off->code;
            $row->user_code = $row->course_type == 1 ? $user_onl->code : $user_off->code;
            $row->is_pass = $row->course_type == 1 ? (isset($result_onl) ? ($result_onl == '1' ? '1' : '0') : '') : (isset
            ($result_off) ? ($result_off == '1' ? '1' : '0') : '');
            $row->is_cert = 1;
            $row->is_report = 0;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function exportTemplate1(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return (new IHRPTemplate1Export($start_date, $end_date))->download('ihrp_template_1_khoa_dao_tao_'. date('d_m_Y') .'.xlsx');
    }
    public function exportTemplate2(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return (new IHRPTemplate2Export($start_date, $end_date))->download('ihrp_template_2_chi_tiet_khoa_dao_tao_'. date('d_m_Y') .'.xlsx');
    }
    public function exportTemplate3(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return (new IHRPTemplate3Export($start_date, $end_date))->download('ihrp_template_3_cap_nhat_ket_qua_dao_tao_'. date('d_m_Y') .'.xlsx');
    }

    public function getTeacher($course_id){
        $teacher = OfflineSchedule::leftJoin('el_training_teacher AS b', 'b.id', '=', 'teacher_main_id')
            ->where('course_id', '=', $course_id)
            ->where('b.status', '=', 1)
            ->pluck('b.name')->toArray();

        return $teacher;
    }
}
