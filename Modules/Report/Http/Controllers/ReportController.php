<?php

namespace Modules\Report\Http\Controllers;

use App\Permission;
use App\Models\Categories\TrainingTeacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Excel;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Report\Entities\HistoryExport;

class ReportController extends Controller
{
    public function index()
    {
        $reports = $this->reportList();
        return view('report::index', [
            'reports' => $reports
        ]);
    }

    public function review(Request $request, $report) {
        $class_name = 'Modules\Report\Http\Controllers\\'. strtoupper($report). 'Controller';
        if (class_exists($class_name)) {
            $controller = new $class_name();
            return $controller->review($request, $report);
        }
        abort(404);
    }

    public function getData(Request $request) {
        $report = $request->report;
        if (!$report) return;
        $class_name = "Modules\Report\Http\Controllers\\". $report . 'Controller';
        if (class_exists($class_name)) {
            $controller = new $class_name();
            return $controller->getData($request);
        }
        abort(404);
    }

    public function export(Request $request)
    {
        $rpt = $request->report;
        $class_name = "Modules\Report\Export\\". $rpt . 'Export';
        if (class_exists($class_name)){
            $report = new $class_name($request);
            return $report->download('report_'.$rpt.'_'. date('d_m_Y') .'.xlsx', Excel::XLSX);

            // \DB::table('el_history_export')->insert([
            //     'class_name' => $rpt,
            //     'report_name' => @$this->reportList()[$rpt],
            //     'request' => json_encode($request->all()),
            //     'user_id' => \Auth::id(),
            //     'status' => 2,
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ]);

            // return redirect()->route('module.report.history_export');
        }
        abort(404);
    }

    public function dataChart(Request $request) {
        $report = $request->report;
        if (!$report) {
            return false;
        }

        $class_name = "Modules\Report\Http\Controllers\\". $report . 'Controller';
        if (class_exists($class_name)) {
            $controller = new $class_name();
            return $controller->dataChart($request);
        }

        abort(404);
    }

    public function filter(Request $request)
    {
        $search = $request->search;
        if ($request->type=='course') {
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            if ($request->course_type==1) {
                $query = OnlineCourse::where('status','=',1);
            }elseif($request->course_type==2) {
                $query = OfflineCourse::where('status','=',1);
            }else{
                return null;
            }
            if ($search) {
                $query->where(function ($join) use ($search){
                    $join->where('name', 'like', '%'. $search .'%');
                    $join->orWhere('code', 'like', '%'. $search .'%');
                });
            }
            if ($from_date && $to_date){
                $query->where('start_date', '>=', date_convert($from_date));
                $query->where('start_date', '<=', date_convert($to_date, '23:59:59'));
            }

            $paginate = $query->paginate(10);
            $data['results'] = $query->get(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
            if ($paginate->nextPageUrl()) {
                $data['pagination'] = ['more' => true];
            }
            return json_result($data);
        }elseif ($request->type=='teacher'){
            return TrainingTeacher::getTeacherSelect2($request);
        }
    }

    public function reportList() {
        return [
            'BC01' => 'Danh sách ký cam kết bồi hoàn',
            'BC02' => 'Danh sách học viên tham gia các khóa đào tạo',
            'BC03' => 'Danh sách khóa đào tạo có chi phí',
            /*'BC04' => 'Danh sách khóa đào tạo ',*/
            'BC05' => 'Danh sách học viên tham gia khóa đào tạo',
            'BC06' => 'Báo cáo giáo vụ',
            'BC07' => 'Báo cáo kết quả khóa học tập trung',
            'BC08' => 'Báo cáo kết quả khóa học Elearning',
            'BC09' => 'Báo cáo đánh giá sau khóa học',
            'BC10' => 'Báo cáo danh sách giảng viên',
            'BC11' => 'Báo cáo đánh giá',
            'BC12' => 'Thống kê đăng ký tham giá khóa học',
            'BC13' => 'Danh sách vi phạm',
            'BC14' => 'Thống kê kết quả đào tạo',
            'BC15' => 'Quá trình đào tạo',
            'BC16' => 'Báo cáo chi tiết kết quả kỳ thi',
            'BC17' => 'Báo cáo số lần thi theo nhóm câu hỏi',
            'BC18' => 'Báo cáo lần truy cập',
            /*'BC19' => 'Báo cáo tổng hợp kết quả đánh giá năng lực',
            'BC20' => 'Báo cáo tổng hợp nhu cầu đào tạo',*/
            'BC21' => 'Thống kê thí sinh trong kỳ thi theo chức danh',
            'BC22' => 'Thống kê tỷ lệ xếp loại trong kỳ thi theo chức danh',
            /*'BC23' => 'Thống kê kết quả khóa học theo chương trình đào tạo',*/
            'BC24' => 'Kế hoạch tự đào tạo',
            'BC25' => 'Tổng hợp báo cáo đào tạo nội bộ',
            'BC26' => 'Báo cáo chi tiết thực hiện đào tạo nội bộ',
            /*'BC27' => 'Báo cáo kết quả thi theo nhóm câu hỏi',*/
            'BC28' => 'Báo cáo thống kê kết quả khảo sát',
            /*'BC29' => 'Báo cáo thống kê lượt truy cập',
            'BC30' => 'Báo cáo thống kê chi tiết lượt truy cập',
            'BC31' => 'Báo cáo thống kê thời lượng truy cập',*/
//            'BC32' => 'Báo cáo thống kê quá trình đào tạo',
//            'BC33' => 'Báo cáo thống kê số lượng khóa học',
            /*'BC34' => 'Báo cáo thống kê số lượng người dùng',*/
            //'BC35' => 'Báo cáo kết quả học tập GG',
            /*'BC36' => 'Báo cáo tình hình tổ chức kỳ thi',
            'BC37' => 'Báo cáo thống kê số lượng video của đơn vị xuất bản trong tháng',
            'BC38' => 'Báo cáo thống kê số lượng người xem video của đơn vị có video xuất bản trong tháng',
            'BC39' => 'Báo cáo thống kê số lượng xem video từng ngày trong tháng',*/
            'BC40' => 'Báo cáo tình hình đào tạo theo kênh phân phối',
            'BC41' => 'Thống kê kết quả đào tạo theo chức danh',
            'BC42' => 'Báo cáo đào tạo',
            'BC43' => 'Báo cáo hiệu quả sau đào tạo',

            //Đem qua module ReportNew chuyển thành BC01 -> BC04
            /*'BC44' => 'Báo cáo số liệu công tác khảo thi',
            'BC45' => 'Báo cáo số liệu điểm thi chi tiết',
            'BC46' => 'Báo cáo cơ cấu đề thi',
            'BC47' => 'Báo cáo tỉ lệ trả lời đúng từng câu hỏi trong ngân hàng câu hỏi',*/
        ];
    }

    public function history(){
        return view('report::export');
    }

    public function download($history_id)
    {
        $history = HistoryExport::find($history_id);
        $storage = \Storage::disk('local');
        $file_name = $storage->path($history->file_name);

        //$file_name = Config('app.datafile.dataroot'). '/uploads/'. $history->file_name;
        if (file_exists($file_name)) {
            return \Response::download($file_name);
        }

        return abort(404);
    }

    public function getDataHistoryExport(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = HistoryExport::query();
        $count = $query->count();
        $query->orderBy('created_at', 'DESC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $file_name = \Storage::disk('local')->path($row->file_name);
            //$file_name = Config('app.datafile.dataroot') . '/uploads/' . $row->file_name;

            $row->size = file_exists($file_name) ? round(filesize($file_name)/1024/1024, 2) : 0;

            $row->download = route('module.report.download', ['history_id' => $row->id]);
        }

        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
}
