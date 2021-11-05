<?php


namespace Modules\DashboardUnit\Exports;

use App\ProfileView;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCondition;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineResult;
use Modules\ReportNew\Entities\ReportNewExportBC05;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Categories\TrainingForm;
use App\TypeCost;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Categories\TrainingType;
use Modules\DashboardUnit\Entities\DashboardUnitByCourse;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use Modules\DashboardUnit\Entities\DashboardTrainingFormModel;
use App\CourseView;

class ExportDashboardUserTrainingFormSheet1 implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow, WithDrawings, WithCharts, WithTitle
{

    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct($unit_user, $child_arr, $area, $unit_type, $unit, $start_date, $end_date) {
        $this->type = 1;
        $this->unit_user = $unit_user;
        $this->child_arr = $child_arr;
        $this->area = $area;
        $this->unit = $unit;
        $this->unit_type = $unit_type;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function map($row): array
    {
        $this->index++;
        $profile = ProfileView::whereUserId(@$row->user_id)->first();
        $training_type_name = @TrainingForm::find(@$row->training_form_id)->name;

        $status_user = '';
        switch (@$profile->status_id) {
            case 0:
                $status_user = trans('backend.inactivity'); break;
            case 1:
                $status_user = trans('backend.doing'); break;
            case 2:
                $status_user = trans('backend.probationary'); break;
            case 3:
                $status_user = trans('backend.pause'); break;
        }
        $user_completed = 0;
        $time_schedule = '';
        $training_unit = '';
        $attendance = '';
        if ($row->course_type == 2){
            $course = OfflineCourse::find($row->course_id);
            $course_time = explode(' ', $course->course_time)[0];

            $schedules = OfflineSchedule::query()
                ->select(['a.end_time', 'a.lesson_date'])
                ->from('el_offline_schedule as a')
                ->where('a.course_id', '=', $row->course_id)
                ->get();
            foreach ($schedules as $schedule){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }
            }

            $condition = OfflineCondition::whereCourseId($row->course_id)->first();
            $percent = OfflineCourse::percent($row->course_id, $row->user_id);
            if ($condition && $percent >= $condition->ratio && $schedules->count() > 0){
                $user_completed = 1;
            }

            $training_unit_arr = $course->training_unit ? json_decode($course->training_unit) : [];
            $areas = Area::whereIn('id', $training_unit_arr)->pluck('name')->toArray();
            $training_unit = count($areas) > 0 ? implode(';', $areas) : '';

            $training_form_name = 'Đào tạo tập trung';

            $result_course = OfflineResult::whereCourseId($row->course_id)->whereUserId($row->user_id)->first();
        }else{
            $course = OnlineCourse::find($row->course_id);
            $training_form_name = 'Đào tạo trực tuyến';
            $course_time = explode(' ', $course->course_time)[0];

            $activities = OnlineCourseActivity::getByCourse($row->course_id);
            $total_complete = 0;
            foreach ($activities as $activity) {
                $check_complete = $activity->isComplete($row->user_id, 1);
                if ($check_complete){
                    $total_complete += 1;
                }
            }

            if ($total_complete == $activities->count() && $activities->count() > 0){
                $user_completed = 1;
            }

            $result_course = OnlineResult::whereCourseId($row->course_id)->whereUserId($row->user_id)->first();
        }

        return [
            $this->index,
            $course->code,
            $course->name,
            $training_form_name,
            $profile->code,
            $profile->full_name,
            $profile->email,
            $profile->phone,
            $profile->unit_name,
            $profile->parent_unit_name,
            $profile->position_name,
            $profile->title_name,
            $training_unit,
            $training_type_name,
            $course_time,
            $attendance,
            get_date($course->start_date),
            get_date($course->end_date),
            $time_schedule,
            $result_course ? $result_course->score : '',
            $user_completed == 1 ? 'Hoàn thành' : 'Không hoàn thành',
            $result_course && $result_course->result == 1 ? 'Đạt' : 'Không đạt',
            $status_user,
            '',
        ];
    }

    public function query()
    {
        $unit_user = $this->unit_user;
        $child_arr = $this->child_arr;

        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->select([
            'el_course_view.course_id',
            'el_course_view.training_form_id',
            'el_course_view.course_type',
            'b.user_id',
        ]);
        $query->join('el_course_register_view as b', function ($sub){
            $sub->on('b.course_id','=','el_course_view.course_id');
            $sub->on('b.course_type','=','el_course_view.course_type');
        });
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);
        $query->where(function ($sub){
            $sub->orWhereExists(function ($sub1){
                $sub1->select(['id'])
                    ->from('el_online_result as result')
                    ->whereColumn('result.user_id', '=', 'b.user_id')
                    ->whereColumn('result.course_id', '=', 'b.course_id')
                    ->whereColumn(DB::raw(1), '=', 'b.course_type');
            });
            $sub->orWhereExists(function ($sub2){
                $sub2->select(['id'])
                    ->from('el_offline_result as result')
                    ->whereColumn('result.user_id', '=', 'b.user_id')
                    ->whereColumn('result.course_id', '=', 'b.course_id')
                    ->whereColumn(DB::raw(2), '=', 'b.course_type');
            });
        });

        if ($this->unit){
            $units = Unit::whereIn('id', explode(';', $this->unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($units->code);

            $query->where(function ($sub_query) use ($unit_id, $units) {
                $sub_query->orWhereIn('b.unit_id', $unit_id);
                $sub_query->orWhere('b.unit_id', '=', $units->id);
            });
        }
        if ($this->unit_type){
            $unit_by_type = Unit::whereType($this->unit_type)->pluck('id')->toArray();
            $query->whereIn('b.unit_id', $unit_by_type);
        }
        if ($this->area){
            $areas = Area::whereIn('id', explode(';', $this->area))->latest('id')->first();
            $area_id = Area::getArrayChild($areas->code);

            $query->leftjoin('el_unit as c','c.id','=','b.unit_id');
            $query->where(function ($sub_query) use ($area_id, $areas) {
                $sub_query->orWhereIn('c.area_id', $area_id);
                $sub_query->orWhere('c.area_id', '=', $areas->id);
            });
        }
        if ($this->start_date){
            $start_date = date_convert($this->start_date);
            $query->where('el_course_view.start_date', '>=', $start_date);
        }
        if ($this->end_date){
            $end_date = date_convert($this->end_date, '23:59:59');
            $query->where('el_course_view.end_date', '<=', $end_date);
        }
        $query->orderBy('el_course_view.course_id', 'ASC');

        $this->count = $query->get()->count();
        return $query;
    }

    public function headings(): array
    {
        $area = Area::find($this->area);
        $export_name = 'Thống kê lượt CBNV theo hình thức đào tạo';

        return [
            [],
            [],
            [],
            [],
            [],
            [$export_name],
            ['Miền', @$area->name],
            ['Thời gian', $this->start_date . ($this->end_date ? ' đến '. $this->end_date : '')],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [
                'STT',
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                'Hình thức đào tạo',
                'Mã nhân viên',
                'Họ và tên',
                'Email',
                'Điện thoại',
                'Đơn vị trực tiếp',
                'Đơn vị quản lý',
                'Chức vụ',
                'Chức danh',
                'Đơn vị đào tạo',
                'Loại hình đào tạo',
                'Thời lượng khóa học',
                'Tổng thời lượng tham gia',
                'Từ ngày',
                'Đến ngày',
                'Thời gian',
                'Điểm',
                'Hoàn thành / Không hoàn thành',
                'Kết quả',
                'Trạng thái',
                'Ghi chú',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];


                $event->sheet->getDelegate()->mergeCells('A6:O6')->getStyle('A6')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A23:X23')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A23:X'.(23 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->addChart($this->lineChart());
                $event->sheet->getDelegate()->addChart($this->pieChart());
            },
        ];
    }

    public function startRow(): int
    {
        return 19;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');
        if ($storage->exists(Config::getConfig('logo'))) {
            $path = $storage->path(Config::getConfig('logo'));
        }else{
            $path = './images/image_default.jpg';
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath($path);
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function charts()
    {
        return [];
    }

    public function countChart(){
        $unit_user = $this->unit_user;
        $child_arr = $this->child_arr;
        $year = date('Y');

        $query = DashboardTrainingFormModel::query();
        $query->select([
            'a.training_form_id',
            'a.training_form_name',
            'total',
        ]);
        $query->from('el_dashboard_training_form AS a');
        $query->whereNotNull('training_form_id');
        // $query->where(function ($sub) use ($unit_user, $child_arr){
        //     $sub->orWhere('unit_id', '=', @$unit_user->id);
        //     $sub->orWhereIn('unit_id', $child_arr);
        // });
        $query->where('year', '=', $year);
        $query->groupBy(['training_form_id','training_form_name','total']);
        return $query->get()->count();
    }

    public function lineChart(){
        $count = $this->countChart();

        $dataSeriesLabels = [];
        for ($i = 24; $i <= (23 + $count); $i++){
            $dataSeriesLabels[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Sheet2!$B$'.$i, null, 1);
        }
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Sheet2!$D$23:$O$23', null, 12),
        ];
        $dataSeriesValues = [];
        for ($i = 24; $i <= (23 + $count); $i++) {
            $dataSeriesValues[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Sheet2!$D$'.$i.':$O$'.$i, null, 12);
        }

        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);
        $title = new Title('');

        $chart = new Chart(
            'chart1',
            $title,
            $legend,
            $plotArea
        );
        $chart->setTopLeftPosition('A10');
        $chart->setBottomRightPosition('H21');

        return $chart;
    }

    public function pieChart(){
        $count = $this->countChart();

        $dataSeriesLabels1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Sheet2!$C$23', null, 1),
        ];
        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Sheet2!$B$24:$B$'.(22 + $count), null, ($count - 1)),
        ];
        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Sheet2!$C$24:$C$'.(22 + $count), null, ($count - 1)),
        ];

        $series1 = new DataSeries(
            DataSeries::TYPE_PIECHART, // plotType
            null, // plotGrouping (Pie charts don't have any grouping)
            range(0, count($dataSeriesValues1) - 1), // plotOrder
            $dataSeriesLabels1, // plotLabel
            $xAxisTickValues1, // plotCategory
            $dataSeriesValues1          // plotValues
        );

        $layout1 = new Layout();
        $layout1->setShowVal(true);

        $plotArea1 = new PlotArea($layout1, [$series1]);
        $legend1 = new Legend(Legend::POSITION_RIGHT, null, false);
        $title1 = new Title('');

        $chart1 = new Chart(
            'chart1', // name
            $title1, // title
            $legend1, // legend
            $plotArea1 // plotArea
        );

        $chart1->setTopLeftPosition('J10');
        $chart1->setBottomRightPosition('O21');

        return $chart1;
    }

    public function title(): string
    {
        return 'Sheet1';
    }
}
