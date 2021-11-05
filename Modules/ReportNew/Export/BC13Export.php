<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Area;
use App\Models\Categories\StudentCost;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineSchedule;

use Modules\Offline\Entities\OfflineStudentCost;
use Modules\ReportNew\Entities\BC13;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC13Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 12;

    public function __construct($param)
    {
        $this->month = $param->month;
        $this->year = $param->year;
        $this->area_id = $param->area_id;
        $this->unit_id = isset($param->unit_id) ? $param->unit_id : null;
    }

    public function query()
    {
        $query = BC13::sql($this->year, $this->unit_id, $this->area_id)->orderBy('id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $unit = Unit::find($row->unit_id_1);
        $area = Area::find(@$unit->area_id);
        $unit_type = UnitType::find(@$unit->type);

        $obj = [];
        $this->index++;

        $obj[] = $this->index;
        $obj[] = @$area->name;
        $obj[] = $row->unit_name_1 .' ('. $row->unit_code_1 .')';
        $obj[] = $row->unit_name_2 .' ('. $row->unit_code_2 .')';
        $obj[] = @$unit_type->name;

        $empl = 0;
        for ($i = 1; $i <= $this->month; $i++){
            $empl += $row->{'t'.$i};
        }
        $avg_user_by_year = number_format($empl/$this->month, 2);

        $obj[] = $avg_user_by_year;
        $obj[] = number_format($row->actual_number_participants,2);
        $obj[] = number_format($row->hits_actual_participation, 2);

        $total_cost = 0;
        $training_cost = TrainingCost::query()->pluck('id')->toArray();
        $student_cost = StudentCost::whereStatus(1)->pluck('id')->toArray();

        $course_cost = json_decode($row->total_organizational_cost, true);
        foreach ($training_cost as $cost_id){
            $course_cost_by_training_cost = isset($course_cost['traing_cost'.$cost_id]) ? $course_cost['traing_cost'.$cost_id] : 0;
            $total_cost += $course_cost_by_training_cost;

            $obj[] = number_format($course_cost_by_training_cost, 2);
        }

        $student = json_decode($row->total_academy_cost, true);
        foreach ($student_cost as $student_id){
            $offline_student_cost = isset($student['student_cost'.$student_id]) ? $student['student_cost'.$student_id] : 0;
            $total_cost += $offline_student_cost;

            $obj[] = number_format($offline_student_cost, 2);
        }

        $obj[] = number_format($total_cost, 2);
        $obj[] = number_format($total_cost / ($avg_user_by_year > 0 ? $avg_user_by_year : 1), 2);
        $obj[] = number_format($total_cost / ($row->actual_number_participants > 0 ? $row->actual_number_participants : 1), 2);
        $obj[] = number_format($total_cost / ($row->hits_actual_participation > 0 ? $row->hits_actual_participation : 1), 2);

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];

        $title_arr[] = 'STT';
        $title_arr[] = 'Khu vực';
        $title_arr[] = 'Chi nhánh/ Phòng Hội sở';
        $title_arr[] = 'Phòng GD/ Phòng nghiệp vụ tại Chi nhánh';
        $title_arr[] = 'Loại ĐV';
        $title_arr[] = 'Tổng nhân sự BQ trong năm';
        $title_arr[] = 'Số người tham gia thực tế';
        $title_arr[] = 'Số lượt tham gia thực tế';

        $traing_cost = TrainingCost::query()->get();
        foreach ($traing_cost as $cost){
            $title_arr[] = $cost->name;
            $this->count_title += 1;
        }

        $student_cost = StudentCost::whereStatus(1)->get();
        foreach ($student_cost as $item){
            $title_arr[] = $item->name;
            $this->count_title += 1;
        }

        $title_arr[] = 'Tổng CP';
        $title_arr[] = 'Chi phí BQ/ Nhân sự';
        $title_arr[] = 'Chi phí BQ/ Người tham gia thực tế';
        $title_arr[] = 'Chi phí BQ/ Lượt';

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO CHI PHÍ ĐÀO TẠO THEO KHU VỰC'],
            [],
            $title_arr
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

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($this->count_title > 26){
                    $num = floor($this->count_title/26);
                    $num_1 = $this->count_title - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->count_title - 1)];
                }

                $event->sheet->getDelegate()->mergeCells('A6:'.$char.'6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:'.$char.'8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:'.$char.(8 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
            },

        ];
    }
    public function startRow(): int
    {
        return 9;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');

        LogoModel::addGlobalScope(new CompanyScope());
        $logo = LogoModel::where('status',1)->first();
        if ($logo) {
            $path = $storage->path($logo->image);
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
}
