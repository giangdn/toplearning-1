<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\TrainingCost;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineRegister;
use Modules\ReportNew\Entities\BC27;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC27Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 6;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->course_type = $param->course_type;
    }

    public function query()
    {
        $query = BC27::sql($this->course_type, $this->from_date, $this->to_date)->orderBy('id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $obj = [];
        $this->index++;

        if ($row->course_type == 1){
            $num_user = OnlineRegister::whereCourseId($row->course_id)->whereStatus(1)->count();
            $course_time = '';
        }else{
            $num_user = OfflineRegister::whereCourseId($row->course_id)->whereStatus(1)->count();
            $course_time = OfflineSchedule::whereCourseId($row->course_id)->count();
        }

        $obj[] = $this->index;
        $obj[] = $row->name .' ('. $row->code .')';
        $obj[] = get_date($row->start_date) . ($row->end_date ? ' đến '. get_date($row->end_date) : '');
        $obj[] = $course_time;
        $obj[] = $num_user;

        TrainingCost::addGlobalScope(new CompanyScope());
        $training_cost = TrainingCost::query()->orderBy('type')->get();
        foreach ($training_cost as $cost){
            if ($row->course_type == 1){
                $course_cost = OnlineCourseCost::whereCourseId($row->course_id)->whereCostId($cost->id)->first();
            }else{
                $course_cost = OfflineCourseCost::whereCourseId($row->course_id)->whereCostId($cost->id)->first();
            }

            $obj[] = isset($course_cost->actual_amount) ? number_format($course_cost->actual_amount, 2) : 0;
        }

        $obj[] = '';

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];
        $title_arr[] = 'STT';
        $title_arr[] = 'Chuyên đề';
        $title_arr[] = 'Thời gian đào tạo';
        $title_arr[] = 'Thời lượng (buổi)';
        $title_arr[] = 'SL học viên';

        $training_cost = TrainingCost::query()->orderBy('type')->get();
        foreach ($training_cost as $cost){
            $title_arr[] = $cost->name;

            $this->count_title += 1;
        }

        $title_arr[] = 'Ghi chú';

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO CHI PHÍ ĐÀO TẠO'],
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
