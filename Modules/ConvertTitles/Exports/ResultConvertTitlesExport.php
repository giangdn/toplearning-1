<?php


namespace Modules\ConvertTitles\Exports;

use App\Profile;
use App\Models\Categories\Titles;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\ConvertTitles\Entities\ConvertTitles;
use Modules\ConvertTitles\Entities\ConvertTitlesRoadmap;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ResultConvertTitlesExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{

    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function map($convert_titles): array
    {
        $this->index++;
        $result = $this->getResultBySubject($convert_titles->training_program_id, $convert_titles->subject_id,
            $convert_titles->training_form, $this->user_id);
        return [
            $this->index,
            $convert_titles->code . ' - ' . $convert_titles->name,
            (empty($result) ? '' : $result->pass_score),
            (empty($result) ? '' : $result->score),
            (empty($result) ? '' : ($result->result == 1 ? 'x' : '')),
            (empty($result) ? '' : ($result->result == 0 ? 'x' : '')),
            (empty($result) ? '' : $convert_titles->training_form == 2 ? $result->note : ''),
        ];
    }

    public function query()
    {
        $profile = Profile::find($this->user_id);
        $title = Titles::where('code', '=', $profile->title_code)->first();

        $query = ConvertTitlesRoadmap::query();
        $query->select([
            'a.subject_id',
            'a.training_program_id',
            'a.training_form',
            'b.name',
            'b.code'
        ])->from('el_convert_titles_roadmap AS a')
            ->leftJoin('el_subject AS b', function ($sub){
                $sub->on('a.training_program_id', '=', 'b.training_program_id');
                $sub->on('a.subject_id', '=', 'b.id');
            })
            ->where('b.status', '=', 1)
            ->where('a.title_id', '=', $title->id)
            ->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $profile = Profile::find($this->user_id);
        return [
            ['Kết quả chuyển đổi chức danh nhân viên ' . $profile->lastname . ' ' . $profile->firstname],
            ['STT', 'Học phần', 'Điểm quy định', 'Kết quả', 'Đánh giá', '', 'Ghi chú'],
            ['', '', '', '', 'Đạt', 'Không đạt', ''],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()
                    ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:G'.(3 + $this->count).'')->applyFromArray([
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
                $event->sheet->mergeCells('A2:A3');
                $event->sheet->mergeCells('B2:B3');
                $event->sheet->mergeCells('C2:C3');
                $event->sheet->mergeCells('D2:D3');
                $event->sheet->mergeCells('E2:F2');
                $event->sheet->mergeCells('G2:G3');
            },

        ];
    }

    public function getResultBySubject($training_program_id, $subject_id, $course_type, $user_id) {
        if ($course_type == 1){
            $query = \DB::query();
            $query->select(['a.*','c.pass_score', 'c.score', 'c.result'])
                ->from('el_online_course AS a')
                ->join('el_online_register AS b', 'b.course_id', '=', 'a.id')
                ->join('el_online_result AS c', 'c.register_id', '=', 'b.id')
                ->where('b.user_id', '=', $user_id)
                ->where('a.subject_id', '=', $subject_id)
                ->where('a.training_program_id', '=', $training_program_id)
                ->where('b.status', '=', 1)
                ->where('a.status', '=', 1);

            if ($query->exists()) {
                return $query->first();
            }
        }else{
            $query = \DB::query();
            $query->select(['a.*','c.pass_score', 'c.score', 'c.result', 'c.note'])
                ->from('el_offline_course AS a')
                ->join('el_offline_register AS b', 'b.course_id', '=', 'a.id')
                ->join('el_offline_result AS c', 'c.register_id', '=', 'b.id')
                ->where('b.user_id', '=', $user_id)
                ->where('a.training_program_id', '=', $training_program_id)
                ->where('a.subject_id', '=', $subject_id)
                ->where('b.status', '=', 1)
                ->where('a.status', '=', 1);

            if ($query->exists()) {
                return $query->first();
            }
        }
        return null;
    }
}
