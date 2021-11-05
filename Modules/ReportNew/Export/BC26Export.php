<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\ReportNew\Entities\BC26;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC26Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->user_id = $param->user_id;
    }

    public function query()
    {
        $query = BC26::sql($this->from_date, $this->to_date, $this->user_id)->orderBy('user_code', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $obj = [];
        $this->index++;
        $course_time = '';
        $course_time_unit_text = '';

        $cost_lecturer = ReportNewExportBC11::query()
            ->where('user_id', '=', $row->user_id)
            ->where('course_id', '=', $row->course_id)
            ->where('course_type', '=', $row->course_type)
            ->sum('cost_lecturer');
        $row->cost_lecturer = $cost_lecturer;

        $cost_tuteurs = ReportNewExportBC11::query()
            ->where('user_id', '=', $row->user_id)
            ->where('course_id', '=', $row->course_id)
            ->where('course_type', '=', $row->course_type)
            ->sum('cost_tuteurs');
        $row->cost_tuteurs = $cost_tuteurs;

        $unit = Unit::whereCode($row->unit_code_1)->first();
        $area = Area::find(@$unit->area_id);

        if ($row->course_type == 2){
            $course = OfflineCourse::find($row->course_id);
            $course_time = $course->course_time;
            $course_time_unit = preg_replace("/[^a-z]/", '', $course->course_time_unit);

            switch ($course_time_unit){
                case 'day': $course_time_unit_text = 'Ngày'; break;
                case 'session': $course_time_unit_text = 'Buổi'; break;
                case 'hour': $course_time_unit_text = 'Giờ'; break;
            }
        }
        $row->course_time = $course_time . ' ' . $course_time_unit_text;

        $obj[] = $this->index;
        $obj[] = $row->course_name .' ('. $row->course_code .')';
        $obj[] = get_date($row->start_date) .' đến '. get_date($row->end_date);
        $obj[] = $row->course_time;
        $obj[] = $row->user_code;
        $obj[] = $row->fullname;
        $obj[] = @$area->name;
        $obj[] = $row->unit_name_1;
        $obj[] = $row->unit_name_2;
        $obj[] = $row->account_number.' ';
        $obj[] = number_format($row->cost_lecturer + ($row->cost_tuteurs ? $row->cost_tuteurs : 0), 2);
        $obj[] = '';

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];
        $title_arr[] = 'STT';
        $title_arr[] = 'Chuyên đề';
        $title_arr[] = 'Thời gian đào tạo';
        $title_arr[] = 'Thời lượng';
        $title_arr[] = 'MSNV';
        $title_arr[] = 'Tên giảng viên';
        $title_arr[] = 'Đơn vị trực tiếp';
        $title_arr[] = 'Đơn vị quản lý';
        $title_arr[] = 'Số tài khoản';
        $title_arr[] = 'Số tiền';
        $title_arr[] = 'Ghi chú';

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO THÙ LAO GIẢNG VIÊN'],
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

                $event->sheet->getDelegate()->mergeCells('A6:K6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:K8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:K'.(8 + $this->index))
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
