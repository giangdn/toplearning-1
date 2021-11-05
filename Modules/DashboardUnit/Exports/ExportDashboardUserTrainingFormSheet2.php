<?php


namespace Modules\DashboardUnit\Exports;

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
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineResult;
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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Modules\DashboardUnit\Entities\DashboardTrainingFormModel;
use App\CourseView;

class ExportDashboardUserTrainingFormSheet2 implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow, WithDrawings, WithTitle
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
        $unit_user = $this->unit_user;
        $child_arr = $this->child_arr;
        $year = date('Y');
        $sum_total = 0;
        for ($i = 1; $i <= 12; $i++){
            $i = ($i < 10) ? '0'.$i : $i;

            if($row->training_form_name != 'Tổng') {
                $course_by_units = $this->count($i, $unit_user, $child_arr, $year, $row->training_form_id);
                $sum_total += $course_by_units;
            } else if($row->training_form_name == 'Tổng') {
                $sum = [];
                foreach($this->total as  $total) {
                    if (isset($sum[$total[0]])) {
                        $sum[ $total[0] ] += $total[1];
                    } else {
                        $sum[ $total[0] ] = $total[1];
                    }
                }
                foreach($sum as $key => $item) {
                    if($key == $i) {
                        $sum_total += $item;
                    }
                }
            }
        }

        $answer_name = [];
        $answer_name[] = $this->index;
        $answer_name[] = $row->training_form_name;
        // $answer_name[] = @$unit_user->name;
        $answer_name[] = $sum_total;

        for ($i = 1; $i <= 12; $i++){
            $i = ($i < 10) ? '0'.$i : $i;

            if($row->training_form_name != 'Tổng') {
                $course_by_units = $this->count($i, $unit_user, $child_arr, $year, $row->training_form_id);
                $answer_name[] = $course_by_units == 0 ? '0' : $course_by_units;
                $this->total[] = [
                    $i,
                    $course_by_units
                ];
            } else if($row->training_form_name == 'Tổng') {
                $sum = [];
                foreach($this->total as  $total) {
                    if (isset($sum[$total[0]])) {
                        $sum[ $total[0] ] += $total[1];
                    } else {
                        $sum[ $total[0] ] = $total[1];
                    }
                }
                foreach($sum as $key => $item) {
                    if($key == $i) {
                        $answer_name[] = $item == 0 ? '0' : $item;
                    }
                }
            } else {
                $answer_name[] = '0';
            }
        }

        return [
           $answer_name,
        ];
    }

    public function query()
    {
        $unit_user = $this->unit_user;
        $child_arr = $this->child_arr;
        $year = date('Y');

        $query = DashboardTrainingFormModel::query();
        $query->select([
            'a.training_form_id',
            'a.training_form_name',
            \DB::raw('count(*) as sum_course'),
            'total',
        ]);
        $query->from('el_dashboard_training_form AS a');
        $query->whereNotNull('training_form_id');
        // $query->where(function ($sub) use ($unit_user, $child_arr){
        //     $sub->orWhere('unit_id', '=', @$unit_user->id);
        //     $sub->orWhereIn('unit_id', $child_arr);
        // });
        $query->where('year', '=', $year);
        $query->orderBy('a.total', 'ASC');
        //$query->orderBy('a.id', 'ASC');

        $query->groupBy(['training_form_id','training_form_name','total']);


        $this->count = $query->get()->count();
        // dd($query->get());
        return $query;
    }

    public function headings(): array
    {
        $area = Area::find($this->area);

        $export_name = 'Thống kê lượt CBNV theo hình thức đào tạo';

        $title_arr1[] = 'STT';
        $title_arr1[] = 'Tên hình thức';
        // $title_arr1[] = 'Đơn vị';
        $title_arr1[] = 'Tổng số CBNV';

        for ($i = 1; $i <= 12; $i++){
            $i = ($i < 10) ? '0'.$i : $i;
            $title_arr1[] = 'Tháng ' . (int) $i;
        }

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
            $title_arr1,
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

                $event->sheet->getDelegate()->getStyle('A23:P23')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A23:P'.(23 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
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

    public function count($i, $unit_user, $child_arr, $year, $training_form_id)
    {
        $first_month = date("Y-$i-01 00:00:00");
        $d = new \DateTime($first_month);
        $last_month = $d->format('Y-m-t 23:59:59');

        $prefix = \DB::getTablePrefix();

//        $online_result = OnlineResult::query()
//            ->select(['id','user_id', 'course_id', DB::raw('1 as course_type') ,'updated_at as updated_at2']);
//        $course_result = OfflineResult::query()
//            ->select(['id','user_id', 'course_id',  DB::raw('2 as course_type') ,'updated_at as updated_at2']);
//        $course_result->union($online_result);
//        $querySql = $course_result->toSql();
//
//        $course_result = DB::table(DB::raw("($querySql) as a"))->mergeBindings($course_result->getQuery());

        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->join('el_course_register_view as b', function ($sub){
            $sub->on('b.course_id','=','el_course_view.course_id');
            $sub->on('b.course_type','=','el_course_view.course_type');
        });
        $query->where('el_course_view.training_form_id', $training_form_id);
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);
        $query->where(\DB::raw('month('.$prefix.'el_course_view.start_date)'), '<=', $i)
            ->where(function ($sub) use ($prefix, $i){
                $sub->orWhereNull('el_course_view.end_date');
                $sub->orWhere(\DB::raw('month('.$prefix.'el_course_view.end_date)'), '>=', $i);
            });
        $query->where(function ($sub) use ($prefix, $i){
            $sub->orWhereExists(function ($sub1) use ($prefix, $i){
                $sub1->select(['id'])
                    ->from('el_online_result as result')
                    ->whereColumn('result.user_id', '=', 'b.user_id')
                    ->whereColumn('result.course_id', '=', 'b.course_id')
                    ->whereColumn(DB::raw(1), '=', 'b.course_type')
                    ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $i);
            });
            $sub->orWhereExists(function ($sub2){
                $sub2->select(['id'])
                    ->from('el_offline_result as result')
                    ->whereColumn('result.user_id', '=', 'b.user_id')
                    ->whereColumn('result.course_id', '=', 'b.course_id')
                    ->whereColumn(DB::raw(2), '=', 'b.course_type');
            });
        });
        /*$query->where('a.start_date','<=', $last_month)
            ->where(function ($sub) use ($first_month, $last_month){
                $sub->where('a.end_date','>=', $last_month);
                $sub->orwhere('a.end_date', '>=', $first_month);
            });*/

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
            $end_date = date_convert($this->end_date,'23:59:59');
            $query->where('el_course_view.end_date', '<=', $end_date);
        }

        $list_course = $query->get();
        $course_by_units = $list_course->count();

        return $course_by_units;
    }

    public function title(): string
    {
        return 'Sheet2';
    }
}
