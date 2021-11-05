<?php


namespace Modules\TrainingPlan\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Categories\TrainingObject;
use Modules\TrainingPlan\Entities\TrainingPlanDetail;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use Modules\TrainingPlan\Entities\TrainingPlan;
use App\TypeCost;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Categories\TrainingCost;

class ExportTemplateTraining implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow
{

    use Exportable;

    protected $index = 0;
    protected $count = 2;
    protected $count_title = 18;
    protected $total = 0;

    public function __construct($plan_id)
    {
        $this->plan_id = $plan_id;
    }

    public function map($row): array
    {
        $answer_name = [];
        return [
           $answer_name,
        ];
    }

    public function query()
    {
        $query = TrainingPlanDetail::query();
        $query->select([
            'tpd.*',
        ]);
        $query->from('el_training_plan_detail AS tpd');
        $query->orderBy('tpd.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $training_costs = TrainingCost::get();
        $plan_id = TrainingPlan::find($this->plan_id);

        $title_arr1[] = 'STT';                              
        $title_arr1[] = trans('lacourse.course_code');                      
        $title_arr1[] = 'Chương trình đào tạo (Mã)';                     
        $title_arr1[] = 'Hình thức đào tạo (Nhập Mã cách nhau dấu phẩy)';             
        $title_arr1[] = 'Loại hình đào tạo (Nhập Mã cách nhau dấu phẩy)';                
        $title_arr1[] = 'Đơn vị tỗ chức (Nhập Mã cách nhau dấu phẩy)';                
        $title_arr1[] = 'Đơn vị tổ chức bên ngoài (Nhập Mã cách nhau dấu phẩy)';         
        $title_arr1[] = 'Đơn vị phối hợp nội bộ (Nhập Mã cách nhau dấu phẩy)';        
        $title_arr1[] = 'Đơn vị phối hợp bên ngoài (Nhập Mã cách nhau dấu phẩy)';                      
        $title_arr1[] = 'Thời lượng đào tạo/Lớp';           
        $title_arr1[] = 'Quý 1';
        $title_arr1[] = 'Quý 2';
        $title_arr1[] = 'Quý 3';
        $title_arr1[] = 'Quý 4';
        $title_arr1[] = 'Số lượng học viên lớp';
        $title_arr1[] = 'Nhu cầu đào tạo CBNV hiện hữu';
        $title_arr1[] = 'Nhu cầu đào tạo CBNV tân tuyễn';
        $title_arr1[] = 'Đối tượng đào tạo (Nhập Mã cách nhau dấu phẩy)';


        foreach ($training_costs as $key => $training_cost){
            $title_arr1[] = $training_cost->name;
            $this->count_title += 1;
        }

        return [
            ['Mẫu Chi tiết kế hoạch đào tạo'],
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
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ];

                $char = $this->getChar($this->count_title);

                $event->sheet->getDelegate()->mergeCells('A1:'.$char.'1')->getStyle('A2')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A1:'.$char.'2')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');
            },
        ];
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getChar($number){
        $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        if ($number > 26){
            $num = floor($number/26);
            $num_1 = $number - ($num * 26);

            $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
        }else{
            $char = $arr_char[($number - 1)];
        }

        return $char;
    }
}
