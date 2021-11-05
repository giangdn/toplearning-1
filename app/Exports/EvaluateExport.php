<?php
namespace App\Exports;

use App\User;
use App\Profile;
use Modules\Online\Entities\OnlineComment;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Modules\Online\Entities\OnlineCourse;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Quiz\Entities\QuizUserSecondary;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class EvaluateExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($id, $course_id, $user_type){
        $this->id = $id;
        $this->course_id = $course_id;
        $this->user_type = $user_type;
    }

    public function map($row): array
    {
        $this->index++;
        $course = OnlineCourse::find($row->course_id);
        if ($row->user_type == 1){
            $profile = Profile::whereUserId($row->user_id)->first();
            $fullname = $profile->getFullName();
            $title = @$profile->titles->name;
            $unit = @$profile->unit->name;
        }else{
            $profile = QuizUserSecondary::find($row->user_id);
            $fullname = $profile->name;
            $title = '';
            $unit = '';
        }
        return [
            $this->index,
            $fullname,
            $unit,
            $title,
            $course->name,
            $row->content,
        ];
    }

    public function query()
    {
        $query = OnlineComment::query();
        $query->where('course_id', $this->course_id);
        $query->where('user_id', $this->id);
        $query->where('user_type', $this->user_type);
        $query->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách học viên bình luận'],
            [
                'STT',
                'Tên',
                'Đơn vị',
                'Chức danh',
                'Khóa học',
                'Bình luận',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:F1');

                $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                ->getStyle('A1:F'.(2 + $this->count).'')
                ->applyFromArray([
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
