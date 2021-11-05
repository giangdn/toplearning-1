<?php

namespace Modules\Indemnify\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\In;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Indemnify\Entities\TotalIndemnify;
use Modules\Offline\Entities\OfflineCourse;

class UserDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($user_id)
    {
        $indem = TotalIndemnify::where('user_id', '=', $user_id)->first();
        $check_indemnify = Indemnify::checkIndemnify($user_id);
        return view('indemnify::backend.userdetail',[
            'user_id'=>$user_id,
            'full_name'=>\DB::table('el_profile_view')->where('user_id','=',$user_id)->value('full_name'),
            'check_indemnify' => $check_indemnify,
            'indem' => $indem
        ]);
    }
    public function getData($user_id,Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = \DB::table('el_indemnify as a')
            ->select("a.commit_amount as cost_commit",
                "b.code",
                "b.name",
                "b.commit_date as date_start",
                "a.cost_student",
                "b.cost_class",
                "a.commit_date",
                "a.id",
                "b.start_date",
                "b.end_date",
                "a.course_id",
                "a.compensated",
                "a.cost_indemnify",
                "a.date_diff",
                "a.contract",
                "a.user_id",
                "a.calculator",
                "a.exemption_amount"
            )
            ->join('el_offline_course as b','a.course_id','=','b.id')
            ->where('a.user_id','=', $user_id)
            ->where('b.status', '=', 1);

        if ($search) {
            $query->where(function ($grquery) use ($search){
                $grquery->orWhere('b.name', 'like', "%". $search ."%");
                $grquery->orWhere('b.code', 'like', "%". $search ."%");
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $retire = Profile::find($row->user_id)->status ;
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->start_commit = get_date($row->date_start);

//            $row->cost_indemnify = number_format($row->cost_indemnify,0,',','.');
            $dayPass =$row->date_start ? now()->diffInDays($row->date_start):0;
            $dayLeft = $row->commit_date - $dayPass;
            if ($retire==1) {
                if ( $dayLeft>= 0) {
                    if (time() > strtotime($row->date_start)) {
                        if ($row->calculator == '-')
                            $costIndemnify = ($dayLeft * $row->cost_commit - $row->exemption_amount) / $row->commit_date;
                        else
                            $costIndemnify = ($dayLeft * $row->cost_commit + $row->exemption_amount) / $row->commit_date;
                    } else {
                        if ($row->calculator == '-')
                            $costIndemnify = $row->cost_commit - $row->exemption_amount;
                        else
                            $costIndemnify = $row->cost_commit + $row->exemption_amount;
                    }

                    $row->cost_indemnify = numberFormat($costIndemnify, 2);
                    $row->date_diff = $dayLeft;
                }else{
                    $costIndemnify=0;
                    $row->cost_indemnify = 0;
                    $row->date_diff = 0;
                }

                $this->saveIndemnify($row->user_id, $row->course_id, $costIndemnify, $dayLeft);
            }
            $row->cost_commit = numberFormat($row->cost_commit,2);
            $row->contract = $row->contract ? $row->contract : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    private function saveIndemnify($user_id,$course_id,$costIndemnify,$dateDiff){
        Indemnify::where(['user_id'=>$user_id,'course_id'=>$course_id])->update(['cost_indemnify'=>$costIndemnify,'date_diff'=>$dateDiff]);
    }
    public function save(Request $request, $user_id)
    {
        $day_off = $request->day_off;
        if (!$day_off){
            json_message('Chưa nhập ngày nghỉ việc', 'error');
        }
        if (date_convert($day_off) < date('Y-m-d')){
            json_message('Ngày nghỉ việc không được nhỏ hơn hiện tại', 'error');
        }

        foreach ($request->course as $index => $item) {
            $course = OfflineCourse::find($item);
            $day = $this->cal_date($course->commit_date, date_convert($day_off));
            $month = intval($day);
            $indem = Indemnify::checkExists($user_id, $item);

            if ($month >= $indem->commit_date){
                $cost_indemnify = 0;
            }else{
                $cost_indemnify = number_format(($indem->commit_amount / $indem->commit_date) * ($indem->commit_date - $month),0);
            }

            Indemnify::updateOrCreate(['course_id' => $item,'user_id' => $user_id],
                    [
                    'cost_indemnify' => str_replace(',', '', $cost_indemnify),
                    'date_diff' => $month >= $indem->commit_date ? 0 : $indem->commit_date - $month,
                ]);
        }

//        $check_indemnify = Indemnify::checkIndemnify($user_id);

            $total = Indemnify::sumCostIndemnify($user_id);
            $percent = (float)$request->percent;
            $exemption_amount = (int)$request->exemption_amount;

            $model = TotalIndemnify::firstOrNew(['user_id' => $user_id]);
            $model->user_id = $user_id;
            $model->percent = $percent;
            $model->total_indemnify = $total;
            $model->exemption_amount = $exemption_amount;
            $model->total_cost = $total;
            $model->day_off = date_convert($day_off);

            $model->save();
            json_result([
                'status' => 'success',
                'message' => 'Cập nhật thành công',
                'redirect' => route('module.indemnify.user', ['id' => $user_id]),
            ]);

    }

    public function saveContract($user_id, Request $request){
        $contract = $request->contract;
        $course_id = $request->course;

        $indem = Indemnify::where('user_id', '=', $user_id)->where('course_id', '=', $course_id)->first();
        $indem->contract = $contract;
        $indem->save();

        json_result([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
        ]);
    }

    public function saveCompensated($user_id, Request $request){
        $compensated = $request->compensated;
        $indem = TotalIndemnify::where('user_id', '=', $user_id)->first();
        $indem->compensated = $compensated;
        $indem->save();
        Indemnify::updateCompensated($user_id,$compensated);
        json_result([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
        ]);
    }

    public function savePercent($user_id, Request $request){
        $percent = $request->percent;
        $indem = TotalIndemnify::where('user_id', '=', $user_id)->first();

        if ($percent < 0 || $percent > 100){
            json_result([
                'status' => 'error',
                'message' => 'Phần trăm từ 0 đến 100',
            ]);
        }

        TotalIndemnify::where('user_id', '=', $user_id)
            ->update([
                'user_id' => $user_id,
                'percent' => $percent,
                'exemption_amount' => ($indem->total_indemnify * $percent) / 100,
                'total_cost' => $indem->total_indemnify - (($indem->total_indemnify * $percent) / 100)
            ]);

        json_result([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
        ]);
    }

    public function saveExemptionAmount($user_id, Request $request){
        $exemption_amount = $request->exemption_amount;
        $indem = TotalIndemnify::where('user_id', '=', $user_id)->first();

        TotalIndemnify::where('user_id', '=', $user_id)
            ->update([
                'user_id' => $user_id,
                'percent' => $indem->percent,
                'exemption_amount' => $exemption_amount,
                'total_cost' => $indem->total_indemnify - $exemption_amount
            ]);

        json_result([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
        ]);
    }

    public function saveTotalCost($user_id, Request $request){
        $total_cost = $request->total_cost;
        $indem = TotalIndemnify::where('user_id', '=', $user_id)->first();

        TotalIndemnify::where('user_id', '=', $user_id)
            ->update([
                'user_id' => $user_id,
                'percent' => $indem->percent,
                'exemption_amount' => $indem->exemption_amount,
                'total_cost' => $total_cost
            ]);

        json_result([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
        ]);
    }

    function cal_date($date1, $date2) {
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - ($years*365*60*60*24)) / (30*60*60*24));
        $days = floor(($diff - ($years*365*60*60*24) - ($months*30*60*60*24))/(60*60*24));

        $total_day = $years*365 + $months*30 + $days;

        return number_format($total_day, 2);
    }
}
