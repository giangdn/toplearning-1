<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Titles;
use App\Models\Categories\TrainingTeacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\ReportNew\Entities\HistoryExport;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;

class ReportNewController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $reports = $this->reportList();
        return view('reportnew::index', [
            'reports' => $reports,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function review(Request $request, $report) {
        $class_name = 'Modules\ReportNew\Http\Controllers\\'. strtoupper($report). 'Controller';
        if (class_exists($class_name)) {
            $controller = new $class_name();
            return $controller->review($request, $report);
        }
        abort(404);
    }

    public function getData(Request $request) {
        $report = $request->report;
        if (!$report) return;
        $class_name = "Modules\ReportNew\Http\Controllers\\". $report . 'Controller';
        if (class_exists($class_name)) {
            $controller = new $class_name();
            return $controller->getData($request);
        }
        abort(404);
    }

    public function export(Request $request)
    {
        $list_report = $this->reportList();

        $rpt = $request->report;
        $name_report = $list_report[$rpt];

        $class_name = "Modules\ReportNew\Export\\". $rpt . 'Export';
        if (class_exists($class_name)){
            $report = new $class_name($request);
            return $report->download(Str::slug($name_report, '_') .'_'. date('d_m_Y') .'.xlsx', Excel::XLSX);

            /*\DB::table('el_history_export_new')->insert([
                'class_name' => $rpt,
                'report_name' => @$this->reportList()[$rpt],
                'request' => json_encode($request->all()),
                'user_id' => \Auth::id(),
                'status' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->route('module.report_new.history_export');*/
        }
        abort(404);
    }

    public function dataChart(Request $request) {
        $report = $request->report;
        if (!$report) {
            return false;
        }

        $class_name = "Modules\ReportNew\Http\Controllers\\". $report . 'Controller';
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
        }elseif ($request->type=='SubjectByTitle'){
            return TrainingRoadmap::getSubjectByTitle($request);
        }elseif ($request->type == 'titleAll'){
            $query = Titles::query();
            $query->where('status', '=', 1);

            if ($search) {
                $query->where('name', 'like', '%'. $search .'%');
            }

            $paginate = $query->paginate(10);
            $data['results'] = $query->get(['id', 'name AS text']);
            if ($paginate->nextPageUrl()) {
                $data['pagination'] = ['more' => true];
            }

            return json_result($data);
        }
    }

    public function reportList() {
        return [
            'BC01' => 'Báo cáo số liệu công tác khảo thi',
            'BC02' => 'Báo cáo số liệu điểm thi chi tiết',
            'BC03' => 'Báo cáo cơ cấu đề thi',
            'BC04' => 'Báo cáo tỉ lệ trả lời đúng từng câu hỏi trong ngân hàng câu hỏi',
            'BC05' => 'Báo cáo học viên tham gia khóa học tập trung / trực tuyến',
            'BC06' => 'Danh sách học viên của đơn vị theo chuyên đề',
            'BC07' => 'Báo cáo quá trình đào tạo của nhân viên',
            'BC08' => 'Tổng hợp tình hình tổ chức các khóa học nội bộ và bên ngoài',
            'BC09' => 'Thống kê tình hình đào tạo nhân viên tân tuyển',
            'BC10' => 'Danh sách CBNV không chấp hành nội quy đào tạo',
            'BC11' => 'Thống kê Giảng viên Đào tạo (Nội bộ & bên ngoài) theo Tháng / Quý / Năm',
            'BC12' => 'Thống kê chi tiết học viên theo đơn vị',
            'BC13' => 'Báo cáo chi phí đào tạo theo khu vực',
            'BC14' => 'Export danh mục',
            'BC15' => 'Báo cáo tổng hợp kết quả theo tháp đào tạo',
            'BC17' => 'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV tân tuyển',
            'BC18' => 'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV có cam kết',
            'BC21' => 'Danh sách các khóa học trực tuyến đang mở',
            'BC22' => 'Danh sách các chuyên đề gộp / tách',
            'BC23' => 'Thống kê tỷ lệ hoàn thành tháp đào tạo theo chức danh',
            'BC24' => 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo đơn vị',
            'BC25' => 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo chuyên đề',
            'BC26' => 'Báo cáo thù lao giảng viên',
            'BC27' => 'Báo cáo chi phí đào tạo',
            'BC28' => 'Báo cáo kết quả chi tiết theo kỳ thi',
            'BC29' => 'Báo cáo kết quả thực hiện so với kế hoạch quý / năm',
            'BC30' => 'Báo cáo kết quả đánh giá khóa học',
        ];
    }

    public function history(){
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('reportnew::export',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
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

            $row->download = route('module.report_new.download', ['history_id' => $row->id]);
        }

        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
}
