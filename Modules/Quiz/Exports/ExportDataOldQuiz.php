<?php
namespace Modules\Quiz\Exports;

use App\Profile;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizDataOld;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;

class ExportDataOldQuiz implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($search, $title, $unit, $result, $start_date, $end_date)
    {
        $this->end_date = $end_date;
        $this->start_date = $start_date;
        $this->unit = $unit;
        $this->title = $title;
        $this->result = $result;
        $this->search = $search;
    }

    public function map($row): array
    {
        return [
            $this->index,
            $row->user_code,
            $row->user_name,
            $row->title,
            $row->area,
            $row->unit,
            $row->department,
            $row->phone, 
            $row->email,
            $row->quiz_code, 
            $row->quiz_name, 
            $row->start_date ? date('d/m/Y', strtotime($row->start_date)) : '',
            $row->end_date ? date('d/m/Y', strtotime($row->end_date)) : '',
            $row->score_essay,
            $row->score_multiple_choice,
            $row->result,
        ];
    }

    public function query(){
        $query = QuizDataOld::query();

        if($this->result) {
            $query->where('result', $this->result);
        }

        if($this->search) {
            $search = $this->search;
            $query->where(function($sub) use ($search){
                $sub->where('user_code','like','%'.$search.'%');
                $sub->orWhere('user_name','like','%'.$search.'%');
                $sub->orWhere('quiz_code','like','%'.$search.'%');
                $sub->orWhere('quiz_code','like','%'.$search.'%');
            });
        }

        if($this->title) {
            $get_title_name = Titles::find($this->title); 
            $query->where('title', $get_title_name->name);
        }

        if( $this->unit ) {
            $get_unit_name = Unit::where('id',$this->unit)->first(); 
            $query->where(function($sub) use ($get_unit_name){
                $sub->where('unit', $get_unit_name->name);
                $sub->orWhere('area', $get_unit_name->name);
            });
        }

        if ($this->start_date) {
            $start_date = date_convert($this->start_date);
            $query->where('start_date', '<=', $start_date);
        }

        if ($this->end_date) {
            $end_date = date_convert($this->end_date);
            $query->where('end_date', '>=', $end_date);
        }
        $query->orderBy('id', 'ASC');
        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách dữ liệu kỳ thi cũ'],
            [
                'STT',
                'Mã nhân viên',
                'Họ và tên',
                'Chức danh',
                'Trực thuộc',
                'Đơn vị',
                'Phòng/Ban/TT',
                'Điện thoại',
                'Email',
                'Mã kỳ thi',
                'Tên kỳ thi',
                'Thời gian bắt đầu',
                'Thời gian kết thúc',
                'Điểm thi trắc nghiệm',
                'Điểm thi tự luận',
                'Kết quả',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:P1');
                $event->sheet->getDelegate()->getStyle('A1:P1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor() ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:P'.(2 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);
            },

        ];
    }

}
