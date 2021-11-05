<?php
namespace Modules\Survey\Exports;

use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\SurveyUserAnswer;
use Modules\Survey\Entities\SurveyUserCategory;
use Modules\Survey\Entities\SurveyUserExport;
use Modules\Survey\Entities\SurveyUserQuestion;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $survey_id;
    protected $count = 0;
    protected $index = 0;
    protected $num_char = 6;
    protected $title_arr = [];

    public function __construct($survey_id)
    {
        $this->survey_id = $survey_id;
    }

    public function map($report): array
    {
        $this->index++;
        $profile = Profile::whereUserId($report->user_id)->first();

        $answer_name = [];
        $answer_name[] = $this->index;
        $answer_name[] = $profile->code;
        $answer_name[] = $profile->full_name;
        $answer_name[] = @$profile->unit->name;
        $answer_name[] = @$profile->titles->name;
        $answer_name[] = $report->more_suggestions;

        foreach ($this->title_arr as $title){
            $query = SurveyUserExport::query()
                ->where('survey_id', '=', $this->survey_id)
                ->where('user_id', '=', $report->user_id)
                ->where('title', '=', $title)
                ->first();

            $answer_name[] = @$query->content;
        }

        return [
            $answer_name,
        ];
    }

    public function query(){
        $query = SurveyUser::query();
        $query->select([
            'user_id',
            'more_suggestions',
        ]);
        $query->where('survey_id', '=', $this->survey_id);
        $query->where('send', '=', 1);
        $query->groupBy(['user_id', 'more_suggestions']);
        $query->orderBy('user_id', 'ASC');

        $this->count = $query->count();
        return $query;

    }

    public function headings(): array
    {
        $survey = Survey::find($this->survey_id);

        $survey_user = SurveyUser::query()
            ->where('survey_id', '=', $this->survey_id)
            ->where('send', '=', 1)
            ->first();

        $query = SurveyUserExport::query()
            ->where('survey_id', '=', $this->survey_id)
            ->where('user_id', '=', @$survey_user->user_id);
        $questions = $query->get(['title']);

        $count_object = Profile::leftJoin('el_titles AS b', 'b.code', '=', 'title_code')
            ->leftJoin('el_unit AS c', 'c.code', '=', 'unit_code')
            ->whereIn('user_id', function ($subquery) {
                $subquery->select(['user_id']);
                $subquery->from('el_survey_object');
                $subquery->where('survey_id', '=', $this->survey_id);
            })
            ->orWhereIn('b.id', function ($subquery) {
                $subquery->select(['title_id']);
                $subquery->from('el_survey_object');
                $subquery->where('survey_id', '=', $this->survey_id);
            })
            ->orWhereIn('c.id', function ($subquery) {
                $subquery->select(['unit_id']);
                $subquery->from('el_survey_object');
                $subquery->where('survey_id', '=', $this->survey_id);
            })->count();

        $count_survey_user = SurveyUser::where('survey_id', '=',$this->survey_id)->where('send', '=', 1)->count();

        $title = [];
        $title[] = 'STT';
        $title[] = 'Mã nhân viên';
        $title[] = 'Họ tên';
        $title[] = 'Đơn vị';
        $title[] = 'Chức danh';
        $title[] = 'Đề xuất khác';
        foreach ($questions as $item) {
            $title[] = $item->title;
            $this->title_arr[] = $item->title;

            $this->num_char += 1;
        }

        return [
            ['Báo cáo kết quả khảo sát'],
            ['Tên khảo sát: ','', $survey->name],
            ['Thời gian bắt đầu: ','', get_date($survey->start_date)],
            ['Thời gian kết thúc: ','', get_date($survey->end_date)],
            ['Tham gia / đối tượng: ','', ($count_survey_user . '/'. $count_object)],
            [' '],

            $title,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'size'      =>  14,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $event->sheet->mergeCells('A1:F1');
                $event->sheet->mergeCells('A2:B2');
                $event->sheet->mergeCells('A3:B3');
                $event->sheet->mergeCells('A4:B4');
                $event->sheet->mergeCells('A5:B5');

                $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A2:A5')->applyFromArray([
                    'font' => [
                        'bold'      =>  true,
                    ],
                ]);

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($this->num_char > 26){
                    $num = floor($this->num_char/26);
                    $num_1 = $this->num_char - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->num_char - 1)];
                }

                $event->sheet->getDelegate()->getStyle('A7:'.$char.''.(7 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },

        ];
    }

}
