<?php
namespace Modules\CourseOld\Exports;

use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\CourseOld\Entities\CourseOld;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class CourseOldExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $search_user, $search_unit, $search_course, $start_date, $end_date;
    public function __construct($search_user,$search_unit,$search_course,$start_date,$end_date){
        $this->search_user = $search_user;
        $this->search_unit = $search_unit;
        $this->search_course = $search_course;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    public function map($row): array
    {
        $data = json_decode($row->data,true);
        $dataRow = [];
        foreach ($data as $index => $item) {
            if(in_array($index, ['Chi phí đi lại','Chi phí lưu trú','Công tác phí','Bình quân CPGV','Chi phí khác','Bình quân CPTC','Bình quân CP Học viên', 'Tổng CP'])){
                $dataRow[] = number_format($item);
            }else{
                $dataRow[] = $item;
            }

        }
        return $dataRow;
    }
    public function query()
    {
        $query = CourseOld::query();
        if($this->search_user){
            $query->where(function($sub_query){
                $sub_query->orWhere('user_code','like','%' . $this->search_user . '%');
                $sub_query->orWhere('full_name','like','%' . $this->search_user . '%');
            });
        }
        if($this->search_unit){
            $query->where(function($sub_query){
                $sub_query->orWhere('unit','like','%' . $this->search_unit . '%');
            });
        }
        if($this->search_course){
            $query->where(function($sub_query){
                $sub_query->orWhere('course_code','like','%' . $this->search_course . '%');
                $sub_query->orWhere('course_name','like','%' . $this->search_course . '%');
            });
        }

        if ($this->start_date){
            $start_date = date_convert($this->start_date);
            $query->where('start_date','>=', $start_date);
        }
        if ($this->end_date){
            $end_date = date_convert($this->end_date);
            $query->where('end_date','<=', $end_date);
        }

        $this->count = $query->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            [
                'STT',
                'Mã nhân viên',
                'Họ tên nhân viên',
                'Email',
                'Khu vực',
                'Điện thoại',
                'Đơn vị trực tiếp',
                'Đơn vị quản lý',
                'Loại đơn vị',
                'Chức vụ',
                'Chức danh',
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                'Đơn vị đào tạo',
                'Hình thức đào tạo (1: online, 2: tập trung)',
                'Thời lượng khóa học',
                'Từ ngày',
                'Đến ngày',
                'Thời gian',
                'Tổng thời lượng tham gia',
                'Tình trạng',
                'Điểm',
                'Kết quả',
                'Chi phí đi lại',
                'Chi phí lưu trú',
                'Công tác phí',
                'Bình quân CPGV',
                'Chi phí khác',
                'Bình quân CPTC',
                'Bình quân CP Học viện',
                'Tổng CP',
                'Ghi chú',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /*$event->sheet->getDelegate()->mergeCells('A1:AF1');

                $event->sheet->getDelegate()->getStyle('A1:AF1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');*/

                $event->sheet->getDelegate()->getStyle('A1:AF'.(1 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

            },

        ];
    }

}
