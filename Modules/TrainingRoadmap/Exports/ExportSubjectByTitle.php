<?php
namespace Modules\TrainingRoadmap\Exports;

use App\Models\Categories\Titles;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportSubjectByTitle implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents{
    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct($title_id, $subject)
    {
        $this->title_id = $title_id;
        $this->subject = $subject;
    }

    public function map($row): array
    {
        $training_forms = json_decode($row->training_form);
        $training_form = (in_array(1,$training_forms) ? 'Online,' : '') . (in_array(2,$training_forms) ? 'Tập trung' : '');

        $this->index++;
        return [
            $this->index,
            $row->subject_code,
            $row->subject_name,
            $training_form,
            $row->completion_time,
            $row->order,
            $row->content,
            get_date($row->created_at, 'd/m/Y'),
            get_date($row->updated_at, 'd/m/Y'),
        ];
    }

    public function query()
    {
        $query = TrainingRoadmap::query();
        $query->select(['a.*' , 'b.code AS subject_code','b.name AS subject_name']);
        $query->from('el_trainingroadmap AS a');
        $query->where('a.title_id', '=', $this->title_id);
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');

        if ($this->subject) {
            $query->where('a.subject_id', '=', $this->subject);
        }

        $query->orderBy('a.order', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $title = Titles::find($this->title_id);
        return [
            ['Danh sách khóa học chương trình khung'],
            ['Chức danh: ' . $title->name],
            [
                'STT',
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                'Hình thức',
                'Thời gian bắt buộc hoàn thành khóa học (Ngày)',
                'Thứ tự',
                'Mô tả',
                'Ngày tạo',
                'Ngày sửa',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:I1');
                $event->sheet->getDelegate()->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');
                $event->sheet->getDelegate()->mergeCells('A2:I2');

                $event->sheet->getDelegate()->getStyle('A3:I'.(3 + $this->count).'')->applyFromArray([
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
                    ],
                ]);
            },

        ];
    }
}