<?php
namespace Modules\Quiz\Exports;

use Maatwebsite\Excel\Concerns\WithCharts;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\Quiz;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class QuestionExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $count = 0;

    public function __construct($category_id)
    {
        $this->category_id = $category_id;
    }

    public function map($row): array
    {
        $status = ($row->status == 1) ? 'Đã duyệt' : 'Chưa duyệt';
        $question = trim(htmlspecialchars(strip_tags($row->question_name)), "\xc2\xa0");
        if ($row->type == 'essay'){
            $answer = ' ';
        }else{
            $answer = htmlspecialchars($row->answer_name).' '.htmlspecialchars($row->matching_answer);
        }

        return [
            $question,
            $answer,
            $status
        ];
    }

    public function query(){
        $query = Question::query();
        $query->select([
            'a.name as question_name',
            'a.type',
            'a.status',
            'b.title as answer_name',
            'b.matching_answer'
        ]);
        $query->from('el_question as a');
        $query->leftJoin('el_question_answer as b', 'b.question_id', '=', 'a.id');
        $query->where('a.category_id', '=', $this->category_id);
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            ['DANH SÁCH CÂU HỎI'],
            [
                'Tên câu hỏi',
                'Câu trả lời',
                'Trạng thái',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:C1');
                $event->sheet->getDelegate()->getStyle('A1:C1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor() ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:C'.(2 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name' => 'Arial',
                    ],
                ]);
            },
        ];
    }
}
